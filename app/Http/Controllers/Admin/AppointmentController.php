<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'staff', 'service']);
        
        // Mặc định hiển thị lịch hẹn trong ngày hôm nay, hoặc lọc theo ngày được chọn
        $selectedDate = $request->input('date', now()->toDateString());
        $query->whereDate('appointment_date', $selectedDate);
        
        // Lọc theo trạng thái nếu có
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $appointments = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.appointments.index', compact('appointments', 'selectedDate'));
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

    public function destroy(Appointment $appointment)
    {
        // Chỉ cho phép xóa lịch hẹn đã hủy hoặc chưa hoàn thành
        if ($appointment->status === 'completed') {
            return back()->withErrors(['error' => 'Không thể xóa lịch hẹn đã hoàn thành.']);
        }

        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Lịch hẹn đã được xóa thành công.');
    }
}
