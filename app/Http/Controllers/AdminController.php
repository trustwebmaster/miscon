<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with statistics and registrations
     */
    public function dashboard(Request $request)
    {
        // Get filter parameters
        $type = $request->get('type');
        $paymentStatus = $request->get('payment_status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $university = $request->get('university');

        // Build query with filters
        $query = Registration::query()->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($university) {
            $query->where('university', $university);
        }

        // Get paginated registrations
        $registrations = $query->paginate(15)->withQueryString();

        // Calculate statistics
        $stats = $this->getStats();

        // Get unique universities for filter dropdown
        $universities = Registration::distinct()->pluck('university')->sort()->values();

        return view('admin.dashboard', compact(
            'registrations',
            'stats',
            'universities',
            'type',
            'paymentStatus',
            'search',
            'dateFrom',
            'dateTo',
            'university'
        ));
    }

    /**
     * Get registration statistics
     */
    private function getStats(): array
    {
        $totalRegistrations = Registration::count();
        $totalStudents = Registration::students()->count();
        $totalAlumni = Registration::alumni()->count();
        
        // Paid statistics
        $paidStudents = Registration::students()->paid()->count();
        $paidAlumni = Registration::alumni()->paid()->count();
        $totalPaid = $paidStudents + $paidAlumni;
        
        // Amount statistics
        $totalAmountCollected = Registration::paid()->sum('amount');
        $studentAmountCollected = Registration::students()->paid()->sum('amount');
        $alumniAmountCollected = Registration::alumni()->paid()->sum('amount');
        
        // Pending payments
        $pendingPayments = Registration::where('payment_status', '!=', 'completed')->count();
        $pendingAmount = Registration::where('payment_status', '!=', 'completed')->sum('amount');
        
        // Today's registrations
        $todayRegistrations = Registration::whereDate('created_at', today())->count();
        $todayPaid = Registration::whereDate('created_at', today())->paid()->count();
        
        // Gender breakdown
        $maleCount = Registration::where('gender', 'male')->count();
        $femaleCount = Registration::where('gender', 'female')->count();

        // Payment status breakdown
        $paymentBreakdown = Registration::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();

        return [
            'total_registrations' => $totalRegistrations,
            'total_students' => $totalStudents,
            'total_alumni' => $totalAlumni,
            'paid_students' => $paidStudents,
            'paid_alumni' => $paidAlumni,
            'total_paid' => $totalPaid,
            'total_amount_collected' => $totalAmountCollected,
            'student_amount_collected' => $studentAmountCollected,
            'alumni_amount_collected' => $alumniAmountCollected,
            'pending_payments' => $pendingPayments,
            'pending_amount' => $pendingAmount,
            'today_registrations' => $todayRegistrations,
            'today_paid' => $todayPaid,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'payment_breakdown' => $paymentBreakdown,
        ];
    }

    /**
     * Export registrations to CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type');
        $paymentStatus = $request->get('payment_status');

        $query = Registration::query()->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        $registrations = $query->get();

        $filename = 'miscon26_registrations_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Reference',
                'Full Name',
                'Type',
                'University',
                'Phone',
                'Email',
                'ID Number',
                'Gender',
                'Level/Year',
                'Amount',
                'Payment Status',
                'Payment Method',
                'Paid At',
                'Registered At',
            ]);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->reference,
                    $reg->full_name,
                    ucfirst($reg->type),
                    $reg->university,
                    $reg->phone,
                    $reg->email ?? 'N/A',
                    $reg->id_number,
                    ucfirst($reg->gender),
                    $reg->level,
                    $reg->amount,
                    ucfirst($reg->payment_status),
                    $reg->payment_method ?? 'N/A',
                    $reg->paid_at ? $reg->paid_at->format('Y-m-d H:i:s') : 'N/A',
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View single registration details
     */
    public function show(Registration $registration)
    {
        return view('admin.registration-details', compact('registration'));
    }
}
