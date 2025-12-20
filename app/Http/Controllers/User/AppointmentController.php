<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->with(['staff', 'service'])
            ->latest()
            ->paginate(10);
        
        return view('user.appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $services = Service::where('is_active', true)->get();
        $staffs = Staff::where('status', 'active')->with('services')->get();
        
        $selectedServiceId = $request->get('service_id');
        $selectedStaffId = $request->get('staff_id');
        
        return view('user.appointments.create', compact('services', 'staffs', 'selectedServiceId', 'selectedStaffId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staffs,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Check if staff provides this service
        $staff = Staff::findOrFail($validated['staff_id']);
        if (!$staff->services->contains($validated['service_id'])) {
            return back()->withErrors(['staff_id' => 'Thợ này không cung cấp dịch vụ này.'])->withInput();
        }

        // Check for duplicate appointments
        $existing = Appointment::where('staff_id', $validated['staff_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existing) {
            return back()->withErrors(['appointment_time' => 'Khung giờ này đã được đặt.'])->withInput();
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Appointment::create($validated);

        return redirect()->route('user.appointments.index')
            ->with('success', 'Đặt lịch thành công! Vui lòng chờ xác nhận.');
    }

    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load(['staff', 'service', 'review']);
        
        return view('user.appointments.show', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể chỉnh sửa lịch hẹn đang chờ xác nhận.']);
        }

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Check for duplicate
        $existing = Appointment::where('staff_id', $appointment->staff_id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existing) {
            return back()->withErrors(['appointment_time' => 'Khung giờ này đã được đặt.'])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('user.appointments.index')
            ->with('success', 'Lịch hẹn đã được cập nhật.');
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        if ($appointment->status === 'completed') {
            return back()->withErrors(['error' => 'Không thể hủy lịch hẹn đã hoàn thành.']);
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('user.appointments.index')
            ->with('success', 'Lịch hẹn đã được hủy.');
    }
}
