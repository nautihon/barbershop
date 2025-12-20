<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        // Doanh thu
        $revenue = $this->getRevenue($period);
        $revenueChart = $this->getRevenueChart($period);
        
        // Lịch hẹn
        $appointments = $this->getAppointmentsStats($period);
        
        // Đơn hàng
        $orders = $this->getOrdersStats($period);
        
        // Dịch vụ phổ biến
        $popularServices = Service::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(10)
            ->get();
        
        // Thợ được chọn nhiều nhất
        $popularStaff = Staff::withCount('appointments')
            ->orderBy('appointments_count', 'desc')
            ->take(10)
            ->get();
        
        // Khách hàng mới
        $newCustomers = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->count();
        
        return view('admin.statistics.index', compact(
            'revenue',
            'revenueChart',
            'appointments',
            'orders',
            'popularServices',
            'popularStaff',
            'newCustomers',
            'period'
        ));
    }

    private function getRevenue($period)
    {
        // Doanh thu từ đơn hàng
        $orderQuery = Order::where('status', '!=', 'cancelled');
        
        // Doanh thu từ lịch hẹn (dịch vụ)
        $appointmentQuery = Appointment::where('status', 'completed')
            ->join('services', 'appointments.service_id', '=', 'services.id');
        
        switch ($period) {
            case 'day':
                $orderQuery->whereDate('created_at', today());
                $appointmentQuery->whereDate('appointments.updated_at', today());
                break;
            case 'week':
                $orderQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $appointmentQuery->whereBetween('appointments.updated_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $orderQuery->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                $appointmentQuery->whereMonth('appointments.updated_at', now()->month)
                      ->whereYear('appointments.updated_at', now()->year);
                break;
            case 'year':
                $orderQuery->whereYear('created_at', now()->year);
                $appointmentQuery->whereYear('appointments.updated_at', now()->year);
                break;
        }
        
        $orderRevenue = $orderQuery->sum('total_amount') ?? 0;
        $appointmentRevenue = $appointmentQuery->sum('services.price') ?? 0;
        
        return $orderRevenue + $appointmentRevenue;
    }

    private function getRevenueChart($period)
    {
        $data = [];
        
        switch ($period) {
            case 'month':
                for ($i = 1; $i <= 12; $i++) {
                    $orderRevenue = Order::where('status', '!=', 'cancelled')
                        ->whereMonth('created_at', $i)
                        ->whereYear('created_at', now()->year)
                        ->sum('total_amount') ?? 0;
                    
                    $serviceRevenue = Appointment::where('status', 'completed')
                        ->whereMonth('updated_at', $i)
                        ->whereYear('updated_at', now()->year)
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price') ?? 0;
                    
                    $data[$i] = $orderRevenue + $serviceRevenue;
                }
                break;
            case 'week':
                for ($i = 0; $i < 7; $i++) {
                    $date = now()->startOfWeek()->addDays($i);
                    $orderRevenue = Order::where('status', '!=', 'cancelled')
                        ->whereDate('created_at', $date)
                        ->sum('total_amount') ?? 0;
                    
                    $serviceRevenue = Appointment::where('status', 'completed')
                        ->whereDate('updated_at', $date)
                        ->join('services', 'appointments.service_id', '=', 'services.id')
                        ->sum('services.price') ?? 0;
                    
                    $data[$date->format('Y-m-d')] = $orderRevenue + $serviceRevenue;
                }
                break;
        }
        
        return $data;
    }

    private function getAppointmentsStats($period)
    {
        $query = Appointment::query();
        
        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];
    }

    private function getOrdersStats($period)
    {
        $query = Order::query();
        
        switch ($period) {
            case 'day':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
        ];
    }
}
