<?php

namespace App\Exports;

use App\Models\MonthlyRevenue;
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyRevenueExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $monthlyRevenue = MonthlyRevenue::where('year', $this->year)
            ->where('month', $this->month)
            ->first();

        if (!$monthlyRevenue) {
            return collect([]);
        }

        // Lấy chi tiết đơn hàng
        $orders = Order::where('status', '!=', 'cancelled')
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->with('user')
            ->get();

        // Lấy chi tiết appointments
        $appointments = Appointment::where('status', 'completed')
            ->whereYear('updated_at', $this->year)
            ->whereMonth('updated_at', $this->month)
            ->with(['user', 'service', 'staff'])
            ->get();

        $data = collect([
            [
                'type' => 'Tổng doanh thu',
                'description' => "Tháng {$this->month}/{$this->year}",
                'amount' => $monthlyRevenue->revenue,
                'date' => '',
            ],
            [
                'type' => 'Doanh thu đơn hàng',
                'description' => '',
                'amount' => $monthlyRevenue->order_revenue,
                'date' => '',
            ],
            [
                'type' => 'Doanh thu dịch vụ',
                'description' => '',
                'amount' => $monthlyRevenue->appointment_revenue,
                'date' => '',
            ],
        ]);

        // Thêm chi tiết đơn hàng
        foreach ($orders as $order) {
            $data->push([
                'type' => 'Đơn hàng',
                'description' => "Đơn hàng #{$order->id} - {$order->user->name}",
                'amount' => $order->total_amount,
                'date' => $order->created_at->format('d/m/Y H:i'),
            ]);
        }

        // Thêm chi tiết appointments
        foreach ($appointments as $appointment) {
            $data->push([
                'type' => 'Dịch vụ',
                'description' => "{$appointment->service->name} - {$appointment->user->name} - {$appointment->staff->name}",
                'amount' => $appointment->service->price,
                'date' => $appointment->updated_at->format('d/m/Y H:i'),
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Loại',
            'Mô tả',
            'Số tiền (VNĐ)',
            'Ngày giờ',
        ];
    }

    public function map($row): array
    {
        return [
            $row['type'],
            $row['description'],
            number_format($row['amount'], 0, ',', '.'),
            $row['date'],
        ];
    }

    public function title(): string
    {
        return "Tháng {$this->month}/{$this->year}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
