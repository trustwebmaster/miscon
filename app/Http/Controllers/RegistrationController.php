<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationConfirmation;
use App\Models\Registration;
use App\Services\PaynowService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    protected PaynowService $paynowService;

    public function __construct(PaynowService $paynowService)
    {
        $this->paynowService = $paynowService;
    }

    /**
     * Store a new registration
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['student', 'alumni'])],
            'full_name' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'id_number' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'level' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check for duplicate registration (ID number must be unique)
        $existingById = Registration::where('id_number', $request->id_number)->first();

        if ($existingById) {
            $idLabel = $request->type === 'student' ? 'registration number' : 'national ID';
            return response()->json([
                'success' => false,
                'message' => "A registration with this {$idLabel} already exists.",
                'errors' => ['id_number' => ["This {$idLabel} is already registered."]],
            ], 422);
        }

        // Create the registration
        $registration = Registration::create([
            'reference' => Registration::generateReference(),
            'type' => $request->type,
            'full_name' => $request->full_name,
            'university' => $request->university,
            'phone' => $request->phone,
            'email' => $request->email,
            'id_number' => $request->id_number,
            'gender' => $request->gender,
            'level' => $request->level,
            'amount' => Registration::getAmount($request->type),
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration created successfully',
            'data' => [
                'id' => $registration->id,
                'reference' => $registration->reference,
                'type' => $registration->type,
                'full_name' => $registration->full_name,
                'amount' => $registration->amount,
            ],
        ], 201);
    }

    /**
     * Register and initiate payment in one atomic request
     * Registration is only saved when payment is initiated
     */
    public function registerAndPay(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // Registration fields
            'type' => ['required', Rule::in(['student', 'alumni'])],
            'full_name' => ['required', 'string', 'max:255'],
            'university' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'id_number' => ['required', 'string', 'max:50'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'level' => ['required', 'string', 'max:50'],
            // Payment fields
            'payment_method' => ['required', Rule::in(['ecocash', 'innbucks', 'onemoney'])],
            'payment_phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check for duplicate registration (ID number must be unique)
        $existingById = Registration::where('id_number', $request->id_number)->first();

        if ($existingById) {
            // If already paid, reject
            if ($existingById->isPaid()) {
                $idLabel = $request->type === 'student' ? 'registration number' : 'national ID';
                return response()->json([
                    'success' => false,
                    'message' => "A registration with this {$idLabel} has already been completed and paid.",
                ], 422);
            }

            // If pending/processing, allow retry with same registration
            // Update email if changed
            $existingById->update(['email' => $request->email]);
            $registration = $existingById;
        } else {
            // Create new registration
            $registration = Registration::create([
                'reference' => Registration::generateReference(),
                'type' => $request->type,
                'full_name' => $request->full_name,
                'university' => $request->university,
                'phone' => $request->phone,
                'email' => $request->email,
                'id_number' => $request->id_number,
                'gender' => $request->gender,
                'level' => $request->level,
                'amount' => Registration::getAmount($request->type),
                'payment_status' => 'pending',
            ]);
        }

        // Format phone number for Paynow
        $formattedPhone = PaynowService::formatPhone($request->payment_phone);
        $paymentMethod = PaynowService::mapPaymentMethod($request->payment_method);

        // Generate email (use phone as email if no real email available)
        $email = $formattedPhone . '@miscon26.co.zw';

        // Create description
        $description = "MISCON26 Registration - {$registration->reference}";

        // Initiate payment with Paynow
        $result = $this->paynowService->initiateMobilePayment(
            $registration->reference,
            $email,
            (float) $registration->amount,
            $description,
            $formattedPhone,
            $paymentMethod
        );

        if (!$result['success']) {
            Log::warning('Payment initiation failed', [
                'registration_id' => $registration->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to initiate payment. Please try again.',
            ], 400);
        }

        // Update registration with payment details
        $registration->update([
            'payment_method' => $request->payment_method,
            'payment_phone' => $formattedPhone,
            'payment_status' => 'processing',
            'paynow_poll_url' => $result['poll_url'],
        ]);

        Log::info('Payment initiated successfully', [
            'registration_id' => $registration->id,
            'reference' => $registration->reference,
            'amount' => $registration->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'registration_id' => $registration->id,
                'reference' => $registration->reference,
                'amount' => $registration->amount,
                'payment_status' => 'processing',
                'instructions' => $result['instructions'] ?? 'Please check your phone and enter your PIN to complete the payment.',
            ],
        ]);
    }

    /**
     * Process payment via Paynow Zimbabwe (for existing registration)
     */
    public function processPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => ['required', 'exists:registrations,id'],
            'payment_method' => ['required', Rule::in(['ecocash', 'innbucks', 'onemoney'])],
            'payment_phone' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $registration = Registration::findOrFail($request->registration_id);

        // Check if already paid
        if ($registration->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'This registration has already been paid.',
            ], 400);
        }

        // Format phone number for Paynow
        $formattedPhone = PaynowService::formatPhone($request->payment_phone);
        $paymentMethod = PaynowService::mapPaymentMethod($request->payment_method);

        // Generate email (use phone as email if no real email available)
        $email = $formattedPhone . '@miscon26.co.zw';

        // Create description
        $description = "MISCON26 Registration - {$registration->reference}";

        // Initiate payment with Paynow
        $result = $this->paynowService->initiateMobilePayment(
            $registration->reference,
            $email,
            (float) $registration->amount,
            $description,
            $formattedPhone,
            $paymentMethod
        );

        if (!$result['success']) {
            Log::warning('Payment initiation failed', [
                'registration_id' => $registration->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to initiate payment. Please try again.',
            ], 400);
        }

        // Update registration with payment details
        $registration->update([
            'payment_method' => $request->payment_method,
            'payment_phone' => $formattedPhone,
            'payment_status' => 'processing',
            'paynow_poll_url' => $result['poll_url'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'registration_id' => $registration->id,
                'reference' => $registration->reference,
                'amount' => $registration->amount,
                'payment_status' => 'processing',
                'instructions' => $result['instructions'] ?? 'Please check your phone and enter your PIN to complete the payment.',
            ],
        ]);
    }

    /**
     * Poll payment status
     */
    public function pollPaymentStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => ['required', 'exists:registrations,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $registration = Registration::findOrFail($request->registration_id);

        // If already paid, return success
        if ($registration->isPaid()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'paid',
                    'paid' => true,
                    'paynow_reference' => $registration->paynow_reference,
                    'reference' => $registration->reference,
                ],
            ]);
        }

        // If no poll URL, payment hasn't been initiated
        if (!$registration->paynow_poll_url) {
            return response()->json([
                'success' => false,
                'message' => 'Payment has not been initiated for this registration.',
            ], 400);
        }

        // Poll Paynow for status
        $result = $this->paynowService->pollTransaction($registration->paynow_poll_url);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to check payment status',
            ], 400);
        }

        $status = strtolower($result['status']);

        // Update registration based on status
        if ($result['paid'] || $status === 'paid') {
            $wasPaid = $registration->isPaid();

            $registration->update([
                'payment_status' => 'completed',
                'paynow_reference' => $result['paynow_reference'],
                'paid_at' => now(),
            ]);

            // Send confirmation email if payment was just completed
            if (!$wasPaid && $registration->email) {
                try {
                    Mail::to($registration->email)->send(new RegistrationConfirmation($registration));
                    Log::info('Registration confirmation email sent', [
                        'registration_id' => $registration->id,
                        'email' => $registration->email,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send registration confirmation email', [
                        'registration_id' => $registration->id,
                        'email' => $registration->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => 'paid',
                    'paid' => true,
                    'paynow_reference' => $result['paynow_reference'],
                    'reference' => $registration->reference,
                    'full_name' => $registration->full_name,
                    'type' => $registration->type,
                    'amount' => $registration->amount,
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
            $registration->update([
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
     * Handle Paynow result callback (server-to-server)
     */
    public function paynowCallback(Request $request): JsonResponse
    {
        Log::info('Paynow callback received', ['data' => $request->all()]);

        $status = $this->paynowService->processCallback();

        if (!$status) {
            Log::error('Failed to process Paynow callback');
            return response()->json(['status' => 'error'], 400);
        }

        $reference = $status->reference();
        $registration = Registration::where('reference', $reference)->first();

        if (!$registration) {
            Log::warning('Registration not found for Paynow callback', ['reference' => $reference]);
            return response()->json(['status' => 'not found'], 404);
        }

        if ($status->paid()) {
            $wasPaid = $registration->isPaid();

            $registration->update([
                'payment_status' => 'completed',
                'paynow_reference' => $status->paynowReference(),
                'paid_at' => now(),
            ]);

            // Send confirmation email if payment was just completed
            if (!$wasPaid && $registration->email) {
                try {
                    Mail::to($registration->email)->send(new RegistrationConfirmation($registration));
                    Log::info('Registration confirmation email sent via callback', [
                        'registration_id' => $registration->id,
                        'email' => $registration->email,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send registration confirmation email via callback', [
                        'registration_id' => $registration->id,
                        'email' => $registration->email,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Payment completed via callback', [
                'reference' => $reference,
                'paynow_reference' => $status->paynowReference(),
            ]);
        } else {
            $paynowStatus = strtolower($status->status());
            if (in_array($paynowStatus, ['cancelled', 'disputed', 'refunded', 'failed'])) {
                $registration->update([
                    'payment_status' => 'failed',
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle Paynow return URL (user redirect after payment)
     */
    public function paynowReturn(Request $request)
    {
        // Redirect back to the registration page
        // The frontend will handle checking the status
        return redirect('/#registration')->with('payment_completed', true);
    }

    /**
     * Check registration status by reference
     */
    public function status(string $reference): JsonResponse
    {
        $registration = Registration::where('reference', $reference)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'reference' => $registration->reference,
                'full_name' => $registration->full_name,
                'type' => $registration->type,
                'amount' => $registration->amount,
                'payment_status' => $registration->payment_status,
                'paid_at' => $registration->paid_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Check registration status by ID number (reg number or national ID)
     */
    public function checkByIdNumber(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_number' => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter your registration number or national ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        $registration = Registration::where('id_number', $request->id_number)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'No registration found with this ID. Please check your registration number or national ID and try again.',
                'found' => false,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration found!',
            'found' => true,
            'data' => [
                'reference' => $registration->reference,
                'full_name' => $registration->full_name,
                'type' => $registration->type,
                'university' => $registration->university,
                'phone' => $registration->phone,
                'id_number' => $registration->id_number,
                'gender' => ucfirst($registration->gender),
                'level' => $registration->level,
                'amount' => $registration->amount,
                'payment_status' => $registration->payment_status,
                'payment_method' => $registration->payment_method ? ucfirst($registration->payment_method) : null,
                'paynow_reference' => $registration->paynow_reference,
                'paid_at' => $registration->paid_at?->format('d M Y, H:i'),
                'registered_at' => $registration->created_at->format('d M Y, H:i'),
                'is_paid' => $registration->isPaid(),
            ],
        ]);
    }

    /**
     * Get registration statistics (for admin)
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_registrations' => Registration::count(),
            'total_paid' => Registration::paid()->count(),
            'total_pending' => Registration::pending()->count(),
            'students' => [
                'total' => Registration::students()->count(),
                'paid' => Registration::students()->paid()->count(),
            ],
            'alumni' => [
                'total' => Registration::alumni()->count(),
                'paid' => Registration::alumni()->paid()->count(),
            ],
            'revenue' => [
                'total' => Registration::paid()->sum('amount'),
                'students' => Registration::students()->paid()->sum('amount'),
                'alumni' => Registration::alumni()->paid()->sum('amount'),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Send a test email to verify SMTP configuration
     */
    public function testEmail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            Mail::raw('This is a test email from MISCON26. Your SMTP settings are working.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('MISCON26 SMTP Test Email');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email. Check your SMTP configuration.',
            ], 500);
        }
    }
}
