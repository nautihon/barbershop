<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        $query = Appointment::where('staff_id', $staff->id)
            ->with(['user', 'service']);
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date !== '') {
            $query->where('appointment_date', $request->date);
        }
        
        $appointments = $query->latest()
            ->paginate(15);
        
        return view('staff.appointments.index', compact('appointments', 'staff'));
    }

    public function show(Appointment $appointment)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($appointment->staff_id !== $staff->id) {
            abort(403, 'Bạn không có quyền xem lịch hẹn này.');
        }
        
        $appointment->load(['user', 'service', 'review']);
        
        return view('staff.appointments.show', compact('appointment'));
    }

    public function complete(Appointment $appointment)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($appointment->staff_id !== $staff->id) {
            abort(403);
        }
        
        if ($appointment->status !== 'confirmed') {
            return back()->withErrors(['error' => 'Chỉ có thể hoàn thành lịch hẹn đã được xác nhận.']);
        }
        
        $appointment->load(['user', 'service']);
        
        // Tính điểm tích lũy (nếu chưa sử dụng điểm)
        $loyaltyPointsEarned = 0;
        if ($appointment->loyalty_points_used == 0) {
            // Tích điểm: 50 điểm cố định khi hoàn thành dịch vụ
            $loyaltyPointsEarned = 50;
            $appointment->user->increment('loyalty_points', $loyaltyPointsEarned);
        }
        
        $appointment->update([
            'status' => 'completed',
            'loyalty_points_earned' => $loyaltyPointsEarned,
        ]);
        
        return back()->with('success', 'Lịch hẹn đã được đánh dấu hoàn thành. ' . 
            ($loyaltyPointsEarned > 0 ? "Khách hàng đã tích được {$loyaltyPointsEarned} điểm." : ''));
    }
}
