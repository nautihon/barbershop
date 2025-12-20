<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyRevenue;
use App\Models\Order;
use App\Models\Appointment;
use App\Exports\MonthlyRevenueExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        
        // Lấy hoặc tạo monthly revenue record
        $monthlyRevenue = MonthlyRevenue::firstOrCreate(
            [
                'year' => $year,
                'month' => $month,
            ],
            [
                'revenue' => 0,
                'order_revenue' => 0,
                'appointment_revenue' => 0,
            ]
        );

        // Tính toán doanh thu thực tế
        $orderRevenue = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_amount') ?? 0;

        $appointmentRevenue = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->whereYear('appointments.updated_at', $year)
            ->whereMonth('appointments.updated_at', $month)
            ->sum('services.price') ?? 0;

        $totalRevenue = $orderRevenue + $appointmentRevenue;

        // Cập nhật nếu chưa đóng
        if (!$monthlyRevenue->is_closed) {
            $monthlyRevenue->update([
                'revenue' => $totalRevenue,
                'order_revenue' => $orderRevenue,
                'appointment_revenue' => $appointmentRevenue,
            ]);
        }

        // Lấy danh sách các tháng đã đóng
        $closedMonths = MonthlyRevenue::where('is_closed', true)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.revenues.index', compact('monthlyRevenue', 'totalRevenue', 'orderRevenue', 'appointmentRevenue', 'year', 'month', 'closedMonths'));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $monthlyRevenue = MonthlyRevenue::where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$monthlyRevenue) {
            return back()->with('error', 'Không tìm thấy dữ liệu doanh thu cho tháng này.');
        }

        $filename = "doanh_thu_{$year}_{$month}.xlsx";
        
        return Excel::download(new MonthlyRevenueExport($year, $month), $filename);
    }

    public function closeMonth(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $monthlyRevenue = MonthlyRevenue::where('year', $year)
            ->where('month', $month)
            ->first();

        if (!$monthlyRevenue) {
            return back()->with('error', 'Không tìm thấy dữ liệu doanh thu cho tháng này.');
        }

        if ($monthlyRevenue->is_closed) {
            return back()->with('error', 'Tháng này đã được kết thúc.');
        }

        // Tính lại doanh thu lần cuối
        $orderRevenue = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_amount') ?? 0;

        $appointmentRevenue = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->whereYear('appointments.updated_at', $year)
            ->whereMonth('appointments.updated_at', $month)
            ->sum('services.price') ?? 0;

        $totalRevenue = $orderRevenue + $appointmentRevenue;

        // Đóng tháng
        $monthlyRevenue->update([
            'revenue' => $totalRevenue,
            'order_revenue' => $orderRevenue,
            'appointment_revenue' => $appointmentRevenue,
            'is_closed' => true,
            'closed_at' => now(),
        ]);

        // Xuất file Excel
        $filename = "doanh_thu_{$year}_{$month}.xlsx";
        Excel::store(new MonthlyRevenueExport($year, $month), "exports/{$filename}", 'public');

        return redirect()->route('admin.revenues.index', ['year' => $year, 'month' => $month])
            ->with('success', 'Đã kết thúc tháng và xuất file Excel thành công!');
    }
}
