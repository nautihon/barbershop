<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Service;
use App\Models\Staff;
use App\Models\StaffLeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Doanh thu từ đơn hàng (tổng tiền trừ đi giảm giá)
        $orders = Order::where('status', '!=', 'cancelled')->get();
        $orderRevenue = $orders->sum(function($order) {
            return (float) $order->total_amount - (float) ($order->discount_amount ?? 0);
        });
        
        // Doanh thu từ dịch vụ (appointments đã hoàn thành, trừ đi giảm giá)
        $appointments = Appointment::where('status', 'completed')
            ->with('service')
            ->get();
        $serviceRevenue = $appointments->sum(function($appointment) {
            if (!$appointment->service) return 0;
            return (float) $appointment->service->price - (float) ($appointment->discount_amount ?? 0);
        });
        
        $totalRevenue = $orderRevenue + $serviceRevenue;
        
        // Doanh thu tháng này (tổng tiền trừ đi giảm giá)
        $monthlyOrders = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();
        $monthlyOrderRevenue = $monthlyOrders->sum(function($order) {
            return (float) $order->total_amount - (float) ($order->discount_amount ?? 0);
        });
        
        $monthlyAppointments = Appointment::where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->with('service')
            ->get();
        $monthlyServiceRevenue = $monthlyAppointments->sum(function($appointment) {
            if (!$appointment->service) return 0;
            return (float) $appointment->service->price - (float) ($appointment->discount_amount ?? 0);
        });
        
        $monthlyRevenue = $monthlyOrderRevenue + $monthlyServiceRevenue;
        
        $stats = [
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'total_customers' => User::where('role', 'user')->count(),
            'total_staff' => Staff::where('status', 'active')->count(),
            'pending_leave_requests' => StaffLeaveRequest::where('status', 'pending')->count(),
            'total_leave_requests' => StaffLeaveRequest::count(),
        ];

        $recent_appointments = Appointment::with(['user', 'staff', 'service'])
            ->whereDate('appointment_date', now()->toDateString())
            ->orderBy('appointment_time', 'asc')
            ->get();

        $recent_orders = Order::with(['user'])
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->get();

        $popular_services = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get();

        $popular_staff = Staff::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(5)
            ->get();

        $recent_leave_requests = StaffLeaveRequest::with('staff')
            ->whereHas('staff')
            ->whereDate('created_at', now()->toDateString())
            ->latest()
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments', 'recent_orders', 'popular_services', 'popular_staff', 'recent_leave_requests'));
    }
}
