<?php

namespace App\Services;

use Paynow\Payments\Paynow;
use Paynow\Core\InitResponse;
use Paynow\Core\StatusResponse;
use Illuminate\Support\Facades\Log;

class PaynowService
{
    protected ?Paynow $paynow = null;
    protected bool $configured = false;
    protected bool $testMode = false;
    protected ?string $merchantEmail = null;

    public function __construct()
    {
        $integrationId = config('services.paynow.integration_id');
        $integrationKey = config('services.paynow.integration_key');
        $returnUrl = config('services.paynow.return_url');
        $resultUrl = config('services.paynow.result_url');
        $this->testMode = config('services.paynow.test_mode', false);
        $this->merchantEmail = config('services.paynow.merchant_email');

        // Check if all required config is present
        if ($integrationId && $integrationKey && $returnUrl && $resultUrl) {
            $this->paynow = new Paynow(
                $integrationId,
                $integrationKey,
                $returnUrl,
                $resultUrl
            );
            $this->configured = true;
        } else {
            Log::warning('Paynow not configured. Missing credentials.', [
                'has_integration_id' => !empty($integrationId),
                'has_integration_key' => !empty($integrationKey),
                'has_return_url' => !empty($returnUrl),
                'has_result_url' => !empty($resultUrl),
            ]);
        }
    }

    /**
     * Check if Paynow is properly configured
     */
    public function isConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * Get the email to use for Paynow (merchant email in test mode)
     */
    protected function getAuthEmail(string $fallbackEmail): string
    {
        // In test mode, Paynow requires the merchant's registered email
        if ($this->testMode && $this->merchantEmail) {
            return $this->merchantEmail;
        }
        return $fallbackEmail;
    }

    /**
     * Initiate a mobile payment (EcoCash, InnBucks, etc.)
     *
     * @param string $reference Unique transaction reference
     * @param string $email Customer's email address
     * @param float $amount Amount to charge
     * @param string $description Payment description
     * @param string $phone Customer's phone number
     * @param string $method Payment method (ecocash, innbucks)
     * @return array
     */
    public function initiateMobilePayment(
        string $reference,
        string $email,
        float $amount,
        string $description,
        string $phone,
        string $method
    ): array {
        // Check if Paynow is configured
        if (!$this->configured || !$this->paynow) {
            Log::error('Paynow payment attempted but not configured', [
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'error' => 'Payment service is not configured. Please contact the administrator.',
            ];
        }

        try {
            // Use merchant email in test mode
            $authEmail = $this->getAuthEmail($email);

            Log::info('Initiating Paynow payment', [
                'reference' => $reference,
                'email' => $authEmail,
                'amount' => $amount,
                'phone' => $phone,
                'method' => $method,
                'test_mode' => $this->testMode,
            ]);

            // Create payment
            $payment = $this->paynow->createPayment($reference, $authEmail);
            $payment->add($description, $amount);

            // Send mobile payment request
            $response = $this->paynow->sendMobile($payment, $phone, $method);

            if ($response->success()) {
                Log::info('Paynow payment initiated successfully', [
                    'reference' => $reference,
                    'poll_url' => $response->pollUrl(),
                    'instructions' => $response->instructions(),
                ]);

                return [
                    'success' => true,
                    'poll_url' => $response->pollUrl(),
                    'instructions' => $response->instructions(),
                    'status' => $response->status,
                ];
            }

            // Get detailed error from response
            $errorMessage = $response->error ?? null;
            $responseData = method_exists($response, 'data') ? $response->data() : [];
            
            Log::warning('Paynow payment initiation failed', [
                'reference' => $reference,
                'error' => $errorMessage,
                'status' => $response->status ?? 'unknown',
                'response_data' => $responseData,
            ]);

            // Provide user-friendly error messages
            $userError = match(strtolower($errorMessage ?? '')) {
                'invalid id.' => 'Payment service configuration error. Please contact support.',
                'invalid integration' => 'Payment service configuration error. Please contact support.',
                '' => 'Payment could not be initiated. Please try again.',
                default => $errorMessage ?? 'Payment initiation failed. Please try again.',
            };

            return [
                'success' => false,
                'error' => $userError,
            ];
        } catch (\Paynow\Payments\InvalidIntegrationException $e) {
            Log::error('Paynow invalid integration', [
                'reference' => $reference,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service configuration error. Please contact the administrator.',
            ];
        } catch (\Exception $e) {
            Log::error('Paynow payment exception', [
                'reference' => $reference,
                'message' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Poll transaction status
     *
     * @param string $pollUrl The poll URL from the init response
     * @return array
     */
    public function pollTransaction(string $pollUrl): array
    {
        try {
            $status = $this->paynow->pollTransaction($pollUrl);

            Log::info('Paynow poll response', [
                'poll_url' => $pollUrl,
                'status' => $status->status(),
                'paid' => $status->paid(),
                'reference' => $status->reference(),
                'paynow_reference' => $status->paynowReference(),
            ]);

            return [
                'success' => true,
                'status' => $status->status(),
                'paid' => $status->paid(),
                'amount' => $status->amount(),
                'reference' => $status->reference(),
                'paynow_reference' => $status->paynowReference(),
                'data' => $status->data(),
            ];
        } catch (\Exception $e) {
            Log::error('Paynow poll exception', [
                'poll_url' => $pollUrl,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Failed to check payment status',
            ];
        }
    }

    /**
     * Process status update callback from Paynow
     *
     * @return StatusResponse|null
     */
    public function processCallback(): ?StatusResponse
    {
        try {
            return $this->paynow->processStatusUpdate();
        } catch (\Exception $e) {
            Log::error('Paynow callback processing failed', [
                'message' => $e->getMessage(),
                'post_data' => $_POST,
            ]);
            return null;
        }
    }

    /**
     * Format phone number for Paynow (remove country code, ensure proper format)
     *
     * @param string $phone
     * @return string
     */
    public static function formatPhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove Zimbabwe country code if present
        if (str_starts_with($phone, '263')) {
            $phone = '0' . substr($phone, 3);
        }

        // Ensure it starts with 0
        if (!str_starts_with($phone, '0')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Map payment method to Paynow method name
     *
     * @param string $method
     * @return string
     */
    public static function mapPaymentMethod(string $method): string
    {
        return match (strtolower($method)) {
            'ecocash' => 'ecocash',
            'innbucks' => 'innbucks',
            'onemoney' => 'onemoney',
            default => 'ecocash',
        };
    }
}
