<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\PaynowService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    protected PaynowService $paynowService;

    public function __construct(PaynowService $paynowService)
    {
        $this->paynowService = $paynowService;
    }

    /**
     * Process donation and initiate payment
     */
    public function pay(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
            'payment_phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate minimum amount
        if ($request->amount < Donation::MIN_AMOUNT) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum donation amount is $' . Donation::MIN_AMOUNT . ' USD.',
            ], 422);
        }

        // Create the donation record
        $donation = Donation::create([
            'reference' => Donation::generateReference(),
            'donor_name' => $request->donor_name ?: 'Anonymous',
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'message' => $request->message,
            'amount' => $request->amount,
            'payment_status' => 'pending',
        ]);

        // Format phone number for Paynow
        $formattedPhone = PaynowService::formatPhone($request->payment_phone);

        // Get payment method from request, default to ecocash if not provided
        $paymentMethod = $request->input('payment_method', 'ecocash');

        // Generate email for Paynow
        $email = $request->donor_email ?: ($formattedPhone . '@donation.miscon26.co.zw');

        // Create description
        $description = "MISCON26 Donation - {$donation->reference} - Support underprivileged students";

        // Initiate payment with Paynow
        $result = $this->paynowService->initiateMobilePayment(
            $donation->reference,
            $email,
            (float) $donation->amount,
            $description,
            $formattedPhone,
            $paymentMethod
        );

        if (!$result['success']) {
            Log::warning('Donation payment initiation failed', [
                'donation_id' => $donation->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to initiate payment. Please try again.',
            ], 400);
        }

        // Update donation with payment details
        $donation->update([
            'payment_method' => $paymentMethod,
            'payment_phone' => $formattedPhone,
            'payment_status' => 'processing',
            'paynow_poll_url' => $result['poll_url'],
        ]);

        Log::info('Donation payment initiated successfully', [
            'donation_id' => $donation->id,
            'reference' => $donation->reference,
            'amount' => $donation->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'donation_id' => $donation->id,
                'reference' => $donation->reference,
                'amount' => $donation->amount,
                'payment_status' => 'processing',
                'instructions' => $result['instructions'] ?? 'Please check your phone and enter your PIN to complete the donation.',
            ],
        ]);
    }

    /**
     * Process donation via web payment (browser redirect to Paynow)
     */
    public function payWeb(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric', 'min:1'],
            'donor_name' => ['nullable', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'], // Email required for web payment
            'donor_phone' => ['nullable', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate minimum amount
        if ($request->amount < Donation::MIN_AMOUNT) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum donation amount is $' . Donation::MIN_AMOUNT . ' USD.',
            ], 422);
        }

        // Create the donation record
        $donation = Donation::create([
            'reference' => Donation::generateReference(),
            'donor_name' => $request->donor_name ?: 'Anonymous',
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'message' => $request->message,
            'amount' => $request->amount,
            'payment_status' => 'pending',
        ]);

        // Create description
        $description = "MISCON26 Donation - {$donation->reference} - Support underprivileged students";

        // Initiate web payment with Paynow (redirects user to Paynow page)
        $result = $this->paynowService->initiateWebPayment(
            $donation->reference,
            $request->donor_email,
            (float) $donation->amount,
            $description
        );

        if (!$result['success']) {
            Log::warning('Donation web payment initiation failed', [
                'donation_id' => $donation->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to initiate payment. Please try again.',
            ], 400);
        }

        // Update donation with payment details
        $donation->update([
            'payment_method' => 'web',
            'payment_status' => 'processing',
            'paynow_poll_url' => $result['poll_url'],
        ]);

        Log::info('Donation web payment initiated successfully', [
            'donation_id' => $donation->id,
            'reference' => $donation->reference,
            'amount' => $donation->amount,
            'redirect_url' => $result['redirect_url'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'donation_id' => $donation->id,
                'reference' => $donation->reference,
                'amount' => $donation->amount,
                'payment_status' => 'processing',
                'redirect_url' => $result['redirect_url'], // URL to redirect user to
            ],
        ]);
    }

    /**
     * Poll donation payment status
     */
    public function pollPaymentStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'donation_id' => ['required', 'exists:donations,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $donation = Donation::findOrFail($request->donation_id);

        // If already paid, return success
        if ($donation->isPaid()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'paid',
                    'paid' => true,
                    'paynow_reference' => $donation->paynow_reference,
                    'reference' => $donation->reference,
                ],
            ]);
        }

        // If no poll URL, payment hasn't been initiated
        if (!$donation->paynow_poll_url) {
            return response()->json([
                'success' => false,
                'message' => 'Payment has not been initiated for this donation.',
            ], 400);
        }

        // Poll Paynow for status
        $result = $this->paynowService->pollTransaction($donation->paynow_poll_url);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to check payment status',
            ], 400);
        }

        $status = strtolower($result['status']);

        // Update donation based on status
        if ($result['paid'] || $status === 'paid') {
            $donation->update([
                'payment_status' => 'completed',
                'paynow_reference' => $result['paynow_reference'],
                'paid_at' => now(),
            ]);

            Log::info('Donation payment completed', [
                'donation_id' => $donation->id,
                'reference' => $donation->reference,
                'amount' => $donation->amount,
                'donor' => $donation->donor_name,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'paid',
                    'paid' => true,
                    'paynow_reference' => $result['paynow_reference'],
                    'reference' => $donation->reference,
                    'amount' => $donation->amount,
                ],
            ]);
        }

        // Handle other statuses
        $statusMapping = [
            'created' => 'processing',
            'sent' => 'processing',
            'pending' => 'processing',
            'awaiting delivery' => 'processing',
            'delivered' => 'processing',
            'cancelled' => 'failed',
            'disputed' => 'failed',
            'refunded' => 'failed',
            'failed' => 'failed',
        ];

        $newStatus = $statusMapping[$status] ?? 'processing';

        if ($newStatus === 'failed') {
            $donation->update([
                'payment_status' => 'failed',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $status,
                'paid' => false,
                'payment_status' => $newStatus,
            ],
        ]);
    }

    /**
     * Handle Paynow callback for donations
     */
    public function paynowCallback(Request $request): JsonResponse
    {
        Log::info('Paynow donation callback received', ['data' => $request->all()]);

        $status = $this->paynowService->processCallback();

        if (!$status) {
            Log::error('Failed to process Paynow donation callback');
            return response()->json(['status' => 'error'], 400);
        }

        $reference = $status->reference();
        $donation = Donation::where('reference', $reference)->first();

        if (!$donation) {
            Log::warning('Donation not found for Paynow callback', ['reference' => $reference]);
            return response()->json(['status' => 'not found'], 404);
        }

        if ($status->paid()) {
            $donation->update([
                'payment_status' => 'completed',
                'paynow_reference' => $status->paynowReference(),
                'paid_at' => now(),
            ]);

            Log::info('Donation payment completed via callback', [
                'reference' => $reference,
                'paynow_reference' => $status->paynowReference(),
                'amount' => $donation->amount,
            ]);
        } else {
            $paynowStatus = strtolower($status->status());
            if (in_array($paynowStatus, ['cancelled', 'disputed', 'refunded', 'failed'])) {
                $donation->update([
                    'payment_status' => 'failed',
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Get donation statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_donations' => Donation::count(),
            'total_paid' => Donation::paid()->count(),
            'total_amount' => Donation::paid()->sum('amount'),
            'students_sponsored' => (int) floor(Donation::paid()->sum('amount') / 45),
            'average_donation' => Donation::paid()->avg('amount') ?? 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
