<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'staff', 'service']);
        
        return view('admin.invoices.show', compact('appointment'));
    }

    public function useLoyaltyPoints(Request $request, Appointment $appointment)
    {
        $request->validate([
            'loyalty_points' => 'required|integer|min:0',
        ]);

        $appointment->load(['user', 'service']);
        $user = $appointment->user;
        $pointsToUse = intval($request->loyalty_points);

        if ($pointsToUse > $user->loyalty_points) {
            return back()->with('error', 'Số điểm tích lũy không đủ.');
        }

        // 1 điểm = 100đ
        $discountAmount = $pointsToUse * 100;
        $servicePrice = $appointment->service->price;
        
        if ($discountAmount > $servicePrice) {
            $discountAmount = $servicePrice;
            $pointsToUse = intval($servicePrice / 100);
        }

        // Cập nhật appointment
        $appointment->update([
            'loyalty_points_used' => $pointsToUse,
            'discount_amount' => $discountAmount,
        ]);

        // Trừ điểm của user
        $user->decrement('loyalty_points', $pointsToUse);

        return back()->with('success', "Đã sử dụng {$pointsToUse} điểm tích lũy, giảm " . number_format($discountAmount) . " VNĐ.");
    }
    
    public function download(Appointment $appointment)
    {
        $appointment->load(['user', 'staff', 'service']);
        
        // Có thể sử dụng PDF library như dompdf, tcpdf, hoặc chỉ hiển thị view
        return view('admin.invoices.print', compact('appointment'));
    }
}
