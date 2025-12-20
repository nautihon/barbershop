<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'staff_id' => 'nullable|exists:staffs,id',
            'service_id' => 'nullable|exists:services,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);
        
        // Kiểm tra quyền
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Kiểm tra đã hoàn thành chưa
        if ($appointment->status !== 'completed') {
            return back()->withErrors(['error' => 'Chỉ có thể đánh giá lịch hẹn đã hoàn thành.']);
        }
        
        // Kiểm tra đã đánh giá chưa
        if ($appointment->review) {
            return back()->withErrors(['error' => 'Bạn đã đánh giá lịch hẹn này rồi.']);
        }

        $validated['user_id'] = Auth::id();
        $validated['staff_id'] = $appointment->staff_id;
        $validated['service_id'] = $appointment->service_id;
        
        Review::create($validated);

        return back()->with('success', 'Đánh giá của bạn đã được gửi. Cảm ơn bạn!');
    }
}
