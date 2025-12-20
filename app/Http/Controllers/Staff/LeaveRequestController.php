<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        $leaveRequests = $staff->leaveRequests()->latest()->get();
        
        return view('staff.leave-requests.index', compact('leaveRequests', 'staff'));
    }

    public function create()
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        return view('staff.leave-requests.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $staff->leaveRequests()->create($validated);

        return redirect()->route('staff.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã được gửi thành công.');
    }

    public function show(StaffLeaveRequest $leaveRequest)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($leaveRequest->staff_id !== $staff->id) {
            abort(403);
        }

        return view('staff.leave-requests.show', compact('leaveRequest', 'staff'));
    }

    public function destroy(StaffLeaveRequest $leaveRequest)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($leaveRequest->staff_id !== $staff->id) {
            abort(403);
        }

        if ($leaveRequest->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể xóa đơn xin nghỉ đang chờ duyệt.']);
        }

        $leaveRequest->delete();

        return redirect()->route('staff.leave-requests.index')
            ->with('success', 'Đơn xin nghỉ đã được xóa thành công.');
    }
}
