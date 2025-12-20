<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $staff = Staff::where('user_id', $user->id)->first();
        
        return view('staff.profile.index', compact('user', 'staff'));
    }
}
