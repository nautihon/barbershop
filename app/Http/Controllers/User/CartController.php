<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = Product::find($id);
            if ($product && $product->is_active && $product->stock >= $quantity) {
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image,
                    'subtotal' => $product->price * $quantity,
                ];
                $total += $product->price * $quantity;
            } else {
                // Xóa sản phẩm không hợp lệ khỏi giỏ hàng
                unset($cart[$id]);
            }
        }
        
        // Cập nhật lại session nếu có sản phẩm bị xóa
        if (count($cart) != count(Session::get('cart', []))) {
            Session::put('cart', $cart);
            if (count($cart) == 0) {
                return redirect()->route('user.cart.index')
                    ->with('error', 'Một số sản phẩm trong giỏ hàng không còn khả dụng.');
            }
        }

        return view('user.cart.index', compact('products', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return back()->withErrors(['error' => 'Sản phẩm không khả dụng.']);
        }

        $cart = Session::get('cart', []);
        $currentQuantity = $cart[$product->id] ?? 0;
        $newQuantity = $currentQuantity + $request->quantity;

        if ($newQuantity > $product->stock) {
            return back()->withErrors(['error' => 'Số lượng vượt quá tồn kho.']);
        }

        $cart[$product->id] = $newQuantity;
        Session::put('cart', $cart);

        return back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);

        if ($request->quantity > $product->stock) {
            return back()->withErrors(['error' => 'Số lượng vượt quá tồn kho.']);
        }

        $cart = Session::get('cart', []);
        $cart[$id] = $request->quantity;
        Session::put('cart', $cart);

        return back()->with('success', 'Giỏ hàng đã được cập nhật.');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}
