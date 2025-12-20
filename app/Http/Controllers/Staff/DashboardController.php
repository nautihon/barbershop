<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        $todayAppointments = Appointment::where('staff_id', $staff->id)
            ->where('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'service'])
            ->orderBy('appointment_time')
            ->get();
        
        $upcomingAppointments = Appointment::where('staff_id', $staff->id)
            ->where('appointment_date', '>', today())
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'service'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(10)
            ->get();
        
        $stats = [
            'today_count' => $todayAppointments->count(),
            'pending_count' => Appointment::where('staff_id', $staff->id)
                ->where('status', 'pending')
                ->count(),
            'completed_count' => Appointment::where('staff_id', $staff->id)
                ->where('status', 'completed')
                ->count(),
        ];
        
        return view('staff.dashboard', compact('staff', 'todayAppointments', 'upcomingAppointments', 'stats'));
    }
}
