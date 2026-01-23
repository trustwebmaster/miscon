<?php

namespace App\Http\Controllers;

use App\Models\GuestSpeaker;
use App\Models\PrayerRequest;
use App\Models\ProgramSchedule;
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

    /**
     * List prayer requests (admin only)
     */
    public function prayerRequests(Request $request)
    {
        $status = $request->get('status');
        
        $query = PrayerRequest::latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $prayerRequests = $query->paginate(20)->withQueryString();
        
        $stats = [
            'total' => PrayerRequest::count(),
            'pending' => PrayerRequest::pending()->count(),
            'prayed' => PrayerRequest::prayed()->count(),
        ];
        
        return view('admin.prayer-requests', compact('prayerRequests', 'stats', 'status'));
    }

    /**
     * Mark prayer request as prayed
     */
    public function markPrayerRequestPrayed(Request $request, PrayerRequest $prayerRequest)
    {
        $prayerRequest->markAsPrayed($request->get('notes'));
        
        return back()->with('success', 'Prayer request marked as prayed.');
    }

    /**
     * List guest speakers
     */
    public function guestSpeakers()
    {
        $speakers = GuestSpeaker::ordered()->get();
        return view('admin.guest-speakers', compact('speakers'));
    }

    /**
     * Store new guest speaker
     */
    public function storeGuestSpeaker(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'topic' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'organization' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
        ]);
        
        GuestSpeaker::create($validated);
        
        return back()->with('success', 'Guest speaker added successfully.');
    }

    /**
     * Update guest speaker
     */
    public function updateGuestSpeaker(Request $request, GuestSpeaker $speaker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:50',
            'topic' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'organization' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $speaker->update($validated);
        
        return back()->with('success', 'Guest speaker updated successfully.');
    }

    /**
     * Delete guest speaker
     */
    public function deleteGuestSpeaker(GuestSpeaker $speaker)
    {
        $speaker->delete();
        return back()->with('success', 'Guest speaker deleted.');
    }

    /**
     * List program schedules
     */
    public function programSchedule()
    {
        $schedules = ProgramSchedule::ordered()->get()->groupBy(fn($s) => $s->event_date->format('Y-m-d'));
        return view('admin.program-schedule', compact('schedules'));
    }

    /**
     * Store new schedule item
     */
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'category' => 'required|in:plenary,workshop,worship,break,meal,fellowship,other',
            'display_order' => 'nullable|integer',
        ]);
        
        ProgramSchedule::create($validated);
        
        return back()->with('success', 'Schedule item added successfully.');
    }

    /**
     * Update schedule item
     */
    public function updateSchedule(Request $request, ProgramSchedule $schedule)
    {
        $validated = $request->validate([
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'speaker' => 'nullable|string|max:255',
            'category' => 'required|in:plenary,workshop,worship,break,meal,fellowship,other',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $schedule->update($validated);
        
        return back()->with('success', 'Schedule item updated successfully.');
    }

    /**
     * Delete schedule item
     */
    public function deleteSchedule(ProgramSchedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Schedule item deleted.');
    }
}
