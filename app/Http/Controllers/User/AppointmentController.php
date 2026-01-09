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
        
        // Hiển thị tất cả staff, không lọc theo ngày
        $staffs = Staff::with(['services', 'schedules', 'leaveRequests'])
            ->get();
        
        $selectedServiceId = $request->get('service_id');
        $selectedStaffId = $request->get('staff_id');
        
        // Nếu có staff_id từ trang show, tự động set ngày là hôm nay
        $selectedDate = $request->get('appointment_date');
        if ($selectedStaffId && !$selectedDate) {
            $selectedDate = now()->toDateString();
        }
        
        // Kiểm tra nếu có staff_id được chọn từ trang show, kiểm tra lịch làm việc hôm nay
        $staffNotWorkingToday = false;
        if ($selectedStaffId) {
            $staff = Staff::find($selectedStaffId);
            if ($staff) {
                $today = now()->toDateString();
                if (!$staff->isWorkingOnDate($today)) {
                    $staffNotWorkingToday = true;
                }
            }
        }
        
        return view('user.appointments.create', compact('services', 'staffs', 'selectedServiceId', 'selectedStaffId', 'selectedDate', 'staffNotWorkingToday'));
    }

    /**
     * Lấy thông tin staff có lịch làm việc vào ngày cụ thể (AJAX)
     */
    public function getAvailableStaffs(Request $request)
    {
        $date = $request->get('date');
        $serviceId = $request->get('service_id');
        $staffId = $request->get('staff_id'); // Lấy staff_id nếu có (khi user đã chọn thợ)
        
        if (!$date) {
            return response()->json(['staffs' => []]);
        }

        // Lấy tất cả staff (không lọc theo status để đảm bảo kiểm tra được tất cả)
        $staffsQuery = Staff::with(['services', 'schedules', 'leaveRequests']);

        // Nếu có staff_id được chọn, chỉ lấy staff đó
        if ($staffId) {
            $staffsQuery->where('id', $staffId);
        } else {
            // Nếu không có staff_id, lọc theo dịch vụ nếu có
            if ($serviceId) {
                $staffsQuery->whereHas('services', function($query) use ($serviceId) {
                    $query->where('services.id', $serviceId);
                });
            }
        }

        $staffs = $staffsQuery->get()->map(function($staff) use ($date) {
            $isWorking = $staff->isWorkingOnDate($date);
            return [
                'id' => $staff->id,
                'name' => $staff->name,
                'specialization' => $staff->specialization,
                'is_working' => $isWorking,
            ];
        });

        return response()->json(['staffs' => $staffs]);
    }

    /**
     * Lấy danh sách khung giờ đã được đặt cho staff và ngày cụ thể (AJAX)
     */
    public function getBookedTimeSlots(Request $request)
    {
        $date = $request->get('date');
        $staffId = $request->get('staff_id');
        
        if (!$date || !$staffId) {
            return response()->json(['booked_times' => []]);
        }

        // Lấy tất cả các appointment đã được đặt (không phải cancelled) cho staff và ngày đó
        $bookedAppointments = Appointment::where('staff_id', $staffId)
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->map(function($time) {
                // Đảm bảo format là HH:MM
                if (is_string($time)) {
                    // Nếu có giây, bỏ đi
                    if (strlen($time) > 5) {
                        return substr($time, 0, 5);
                    }
                    return $time;
                }
                // Nếu là time object, format lại
                if ($time instanceof \DateTime) {
                    return $time->format('H:i');
                }
                return $time;
            })
            ->toArray();

        return response()->json(['booked_times' => $bookedAppointments]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'staff_id' => 'required|exists:staffs,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        // Nếu chọn ngày hiện tại, kiểm tra giờ phải sau giờ hiện tại
        if ($validated['appointment_date'] == date('Y-m-d')) {
            $currentTime = date('H:i');
            $selectedTime = $validated['appointment_time'];
            if ($selectedTime <= $currentTime) {
                return back()->withErrors(['appointment_time' => 'Vui lòng chọn khung giờ sau giờ hiện tại.'])->withInput();
            }
        }

        // Check if staff provides this service
        $staff = Staff::findOrFail($validated['staff_id']);
        if (!$staff->services->contains($validated['service_id'])) {
            return back()->withErrors(['staff_id' => 'Thợ này không cung cấp dịch vụ này.'])->withInput();
        }

        // Kiểm tra staff có lịch làm việc vào ngày được chọn không (không kiểm tra status)
        if (!$staff->isWorkingOnDate($validated['appointment_date'])) {
            return back()->withErrors(['appointment_date' => 'Thợ này không làm việc vào ngày này.'])->withInput();
        }
        
        // Kiểm tra staff có xin nghỉ vào ngày được chọn không
        if ($staff->isOnLeaveOnDate($validated['appointment_date'])) {
            return back()->withErrors(['appointment_date' => 'Thợ này đã xin nghỉ vào ngày này.'])->withInput();
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
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        // Nếu chọn ngày hiện tại, kiểm tra giờ phải sau giờ hiện tại
        if ($validated['appointment_date'] == date('Y-m-d')) {
            $currentTime = date('H:i');
            $selectedTime = $validated['appointment_time'];
            if ($selectedTime <= $currentTime) {
                return back()->withErrors(['appointment_time' => 'Vui lòng chọn khung giờ sau giờ hiện tại.'])->withInput();
            }
        }

        // Kiểm tra staff có sẵn sàng làm việc vào ngày được chọn không
        $staff = $appointment->staff;
        if (!$staff->isAvailableOnDate($validated['appointment_date'])) {
            if (!$staff->isWorkingOnDate($validated['appointment_date'])) {
                return back()->withErrors(['appointment_date' => 'Thợ này không làm việc vào ngày này.'])->withInput();
            }
            if ($staff->isOnLeaveOnDate($validated['appointment_date'])) {
                return back()->withErrors(['appointment_date' => 'Thợ này đã xin nghỉ vào ngày này.'])->withInput();
            }
            return back()->withErrors(['appointment_date' => 'Thợ này không sẵn sàng vào ngày này.'])->withInput();
        }

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
