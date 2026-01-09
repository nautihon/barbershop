<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);
        
        // Mặc định hiển thị đơn trong ngày hôm nay, hoặc lọc theo ngày được chọn
        $selectedDate = $request->input('date', now()->toDateString());
        $query->whereDate('created_at', $selectedDate);
        
        $orders = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders', 'selectedDate'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }
}
