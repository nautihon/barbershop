<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'staff', 'service'])
            ->latest()
            ->paginate(15);
        
        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'staff', 'service', 'review']);
        
        return view('admin.appointments.show', compact('appointment'));
    }

    public function confirm(Appointment $appointment)
    {
        if ($appointment->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể xác nhận lịch hẹn đang chờ.']);
        }

        $appointment->update(['status' => 'confirmed']);

        return back()->with('success', 'Lịch hẹn đã được xác nhận.');
    }

    public function cancel(Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return back()->withErrors(['error' => 'Không thể hủy lịch hẹn đã hoàn thành.']);
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Lịch hẹn đã được hủy.');
    }
}
