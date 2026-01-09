<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffLeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = StaffLeaveRequest::with('staff');
        
        // Mặc định hiển thị đơn trong ngày hôm nay, hoặc lọc theo ngày được chọn
        $selectedDate = $request->input('date', now()->toDateString());
        $query->whereDate('created_at', $selectedDate);
        
        $leaveRequests = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.leave-requests.index', compact('leaveRequests', 'selectedDate'));
    }

    public function show(StaffLeaveRequest $leaveRequest)
    {
        $leaveRequest->load('staff');
        
        return view('admin.leave-requests.show', compact('leaveRequest'));
    }

    public function approve(StaffLeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'approved',
            'admin_note' => request('admin_note'),
        ]);

        // Tự động cập nhật status của staff dựa trên lịch làm việc và ngày hiện tại
        $staff = $leaveRequest->staff;
        $dynamicStatus = $staff->dynamic_status;
        $staff->update(['status' => $dynamicStatus]);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã được duyệt. Trạng thái hoạt động của staff đã được tự động cập nhật.');
    }

    public function reject(StaffLeaveRequest $leaveRequest)
    {
        $validated = request()->validate([
            'admin_note' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'admin_note' => $validated['admin_note'],
        ]);

        // Tự động cập nhật status của staff dựa trên lịch làm việc và ngày hiện tại
        $staff = $leaveRequest->staff;
        $dynamicStatus = $staff->dynamic_status;
        $staff->update(['status' => $dynamicStatus]);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã bị từ chối. Trạng thái hoạt động của staff đã được tự động cập nhật.');
    }
}
