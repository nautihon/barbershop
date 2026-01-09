<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->paginate(10);
        
        return view('user.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        
        return view('user.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return back()->withErrors(['error' => 'Giỏ hàng trống.']);
        }

        $total = 0;
        $orderItems = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            
            if (!$product) {
                return back()->withErrors(['error' => "Sản phẩm không tồn tại."])->withInput();
            }
            
            if (!$product->is_active) {
                return back()->withErrors(['error' => "Sản phẩm {$product->name} không còn hoạt động."])->withInput();
            }
            
            if ($product->stock < $quantity) {
                return back()->withErrors(['error' => "Sản phẩm {$product->name} chỉ còn {$product->stock} sản phẩm trong kho."])->withInput();
            }

            $price = $product->price;
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }
        
        if ($total <= 0) {
            return back()->withErrors(['error' => 'Tổng tiền không hợp lệ.'])->withInput();
        }

        // Xử lý điểm tích lũy
        $loyaltyPointsUsed = 0;
        $discountAmount = 0;
        $user = Auth::user();
        
        if (!empty($validated['loyalty_points']) && $validated['loyalty_points'] > 0) {
            $pointsToUse = min(intval($validated['loyalty_points']), $user->loyalty_points);
            if ($pointsToUse > 0) {
                // 1 điểm = 100đ
                $discountAmount = min($pointsToUse * 100, $total);
                $loyaltyPointsUsed = intval($discountAmount / 100);
                $total -= $discountAmount;
                $user->decrement('loyalty_points', $loyaltyPointsUsed);
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $total + $discountAmount, // Tổng ban đầu
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'phone' => $validated['phone'],
            'notes' => $validated['notes'] ?? null,
            'loyalty_points_used' => $loyaltyPointsUsed,
            'discount_amount' => $discountAmount,
        ]);

        foreach ($orderItems as $item) {
            $order->orderItems()->create($item);
            
            // Update product stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->decrement('stock', $item['quantity']);
            }
        }

        // Tích điểm tích lũy (nếu chưa sử dụng điểm)
        $loyaltyPointsEarned = 0;
        if ($order->loyalty_points_used == 0) {
            // Tích điểm: 100 điểm cố định cho mỗi đơn hàng
            $loyaltyPointsEarned = 100;
            Auth::user()->increment('loyalty_points', $loyaltyPointsEarned);
            $order->update(['loyalty_points_earned' => $loyaltyPointsEarned]);
        }

        // Xóa giỏ hàng khỏi session
        Session::forget('cart');
        
        // Xóa giỏ hàng khỏi database
        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('user.orders.show', $order)
            ->with('success', 'Đơn hàng đã được tạo thành công!' . 
                ($loyaltyPointsEarned > 0 ? " Bạn đã tích được {$loyaltyPointsEarned} điểm." : ''));
    }

    public function cancel(Order $order)
    {
        // Kiểm tra quyền truy cập
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Chỉ cho phép hủy nếu đơn hàng chưa được xác nhận
        if ($order->status !== 'pending') {
            return back()->withErrors(['error' => 'Chỉ có thể hủy đơn hàng khi chưa được xác nhận.']);
        }

        // Hoàn lại số lượng sản phẩm vào kho
        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        $user = Auth::user();

        // Hoàn lại điểm tích lũy nếu đã sử dụng
        if ($order->loyalty_points_used > 0) {
            $user->increment('loyalty_points', $order->loyalty_points_used);
        }

        // Trừ điểm tích lũy nếu đã tích (nếu có)
        if ($order->loyalty_points_earned > 0) {
            $user->decrement('loyalty_points', $order->loyalty_points_earned);
        }

        // Cập nhật trạng thái đơn hàng thành 'cancelled'
        $order->update(['status' => 'cancelled']);

        return redirect()->route('user.orders.show', $order)
            ->with('success', 'Đơn hàng đã được hủy thành công.');
    }
}
