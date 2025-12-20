<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function show(Staff $staff)
    {
        $staff->load(['services', 'hairstyles', 'reviews.user']);
        
        return view('user.staffs.show', compact('staff'));
    }
}
