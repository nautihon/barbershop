<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Lấy lịch sử tích điểm từ đơn hàng
        $orders = Order::where('user_id', $user->id)
            ->where('loyalty_points_earned', '>', 0)
            ->orWhere('loyalty_points_used', '>', 0)
            ->latest()
            ->take(10)
            ->get();
        
        // Lấy lịch sử tích điểm từ appointments
        $appointments = Appointment::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('loyalty_points_earned', '>', 0)
                      ->orWhere('loyalty_points_used', '>', 0);
            })
            ->with(['service', 'staff'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('user.profile.index', compact('user', 'orders', 'appointments'));
    }
}
