<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
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
     * Process payment (simulated for now)
     */
    public function processPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => ['required', 'exists:registrations,id'],
            'payment_method' => ['required', Rule::in(['ecocash', 'innbucks'])],
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

        // Update registration with payment details
        $registration->update([
            'payment_method' => $request->payment_method,
            'payment_phone' => $request->payment_phone,
            'payment_status' => 'processing',
        ]);

        // Simulate Paynow payment processing
        // In production, this would integrate with the actual Paynow API
        $paynowReference = 'PN-' . strtoupper(substr(md5(uniqid()), 0, 10));

        // Simulate successful payment (in production, this would be a webhook callback)
        $registration->markAsPaid($paynowReference);

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => [
                'registration_id' => $registration->id,
                'reference' => $registration->reference,
                'paynow_reference' => $paynowReference,
                'amount' => $registration->amount,
                'payment_status' => $registration->payment_status,
                'full_name' => $registration->full_name,
                'type' => $registration->type,
                'phone' => $registration->phone,
            ],
        ]);
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
}
