<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffLeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = StaffLeaveRequest::with('staff')
            ->latest()
            ->paginate(15);
        
        return view('admin.leave-requests.index', compact('leaveRequests'));
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

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã được duyệt.');
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

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã bị từ chối.');
    }
}
