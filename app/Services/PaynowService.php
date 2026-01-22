<?php

namespace App\Services;

use Paynow\Payments\Paynow;
use Paynow\Core\InitResponse;
use Paynow\Core\StatusResponse;
use Illuminate\Support\Facades\Log;

class PaynowService
{
    protected Paynow $paynow;

    public function __construct()
    {
        $this->paynow = new Paynow(
            config('services.paynow.integration_id'),
            config('services.paynow.integration_key'),
            config('services.paynow.return_url'),
            config('services.paynow.result_url')
        );
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
        try {
            // Create payment
            $payment = $this->paynow->createPayment($reference, $email);
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

            Log::warning('Paynow payment initiation failed', [
                'reference' => $reference,
                'error' => $response->error ?? 'Unknown error',
            ]);

            return [
                'success' => false,
                'error' => $response->error ?? 'Payment initiation failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paynow payment exception', [
                'reference' => $reference,
                'message' => $e->getMessage(),
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
