<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::with('user', 'services')->latest()->paginate(10);
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('admin.staffs.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email|unique:users,email',
            'phone' => 'required|string|max:20',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'password' => 'required|string|min:8',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'staff',
            'phone' => $validated['phone'],
        ]);

        // Create staff
        $staffData = [
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'specialization' => $validated['specialization'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('avatar')) {
            $staffData['avatar'] = $request->file('avatar')->store('staffs', 'public');
        }

        $staff = Staff::create($staffData);

        // Assign services
        if ($request->has('services')) {
            $staff->services()->attach($validated['services']);
        }

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Nhân viên đã được tạo thành công.');
    }

    public function show(Staff $staff)
    {
        $staff->load('user', 'services', 'schedules', 'appointments');
        return view('admin.staffs.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $services = Service::where('is_active', true)->get();
        $staff->load('services');
        return view('admin.staffs.edit', compact('staff', 'services'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staff->id . '|unique:users,email,' . $staff->user_id,
            'phone' => 'required|string|max:20',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        if ($request->hasFile('avatar')) {
            if ($staff->avatar) {
                \Storage::disk('public')->delete($staff->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('staffs', 'public');
        }

        $staff->update($validated);
        $staff->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Update services
        if ($request->has('services')) {
            $staff->services()->sync($validated['services']);
        } else {
            $staff->services()->detach();
        }

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Nhân viên đã được cập nhật thành công.');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->avatar) {
            \Storage::disk('public')->delete($staff->avatar);
        }
        
        $staff->services()->detach();
        $staff->user->delete();
        $staff->delete();

        return redirect()->route('admin.staffs.index')
            ->with('success', 'Nhân viên đã được xóa thành công.');
    }

    public function assignServices(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        $staff->services()->sync($validated['services']);

        return back()->with('success', 'Dịch vụ đã được gán thành công.');
    }

    public function schedule(Staff $staff)
    {
        $staff->load('schedules');
        return view('admin.staffs.schedule', compact('staff'));
    }

    public function storeSchedule(Request $request, Staff $staff)
    {
        $schedules = $request->input('schedules', []);
        
        $staff->schedules()->delete();

        foreach ($schedules as $day => $schedule) {
            if (isset($schedule['enabled']) && $schedule['enabled'] == '1') {
                $staff->schedules()->create([
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }

        return redirect()->route('admin.staffs.show', $staff)
            ->with('success', 'Lịch làm việc đã được cập nhật.');
    }
}
