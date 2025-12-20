<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffHairstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HairstyleController extends Controller
{
    public function index()
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        $hairstyles = $staff->hairstyles()->latest()->get();
        
        return view('staff.hairstyles.index', compact('hairstyles', 'staff'));
    }

    public function create()
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        return view('staff.hairstyles.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        $staff->hairstyles()->create($validated);

        return redirect()->route('staff.hairstyles.index')
            ->with('success', 'Kiểu tóc đã được thêm thành công.');
    }

    public function show(StaffHairstyle $hairstyle)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($hairstyle->staff_id !== $staff->id) {
            abort(403);
        }

        return view('staff.hairstyles.show', compact('hairstyle', 'staff'));
    }

    public function edit(StaffHairstyle $hairstyle)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($hairstyle->staff_id !== $staff->id) {
            abort(403);
        }

        return view('staff.hairstyles.edit', compact('hairstyle', 'staff'));
    }

    public function update(Request $request, StaffHairstyle $hairstyle)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($hairstyle->staff_id !== $staff->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($hairstyle->image) {
                Storage::disk('public')->delete($hairstyle->image);
            }
            $validated['image'] = $request->file('image')->store('hairstyles', 'public');
        }

        $hairstyle->update($validated);

        return redirect()->route('staff.hairstyles.index')
            ->with('success', 'Kiểu tóc đã được cập nhật thành công.');
    }

    public function destroy(StaffHairstyle $hairstyle)
    {
        $staff = Staff::where('user_id', Auth::id())->firstOrFail();
        
        if ($hairstyle->staff_id !== $staff->id) {
            abort(403);
        }

        if ($hairstyle->image) {
            Storage::disk('public')->delete($hairstyle->image);
        }

        $hairstyle->delete();

        return redirect()->route('staff.hairstyles.index')
            ->with('success', 'Kiểu tóc đã được xóa thành công.');
    }
}
