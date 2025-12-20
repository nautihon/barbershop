<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);
        
        return view('user.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            abort(404);
        }
        
        return view('user.products.show', compact('product'));
    }
}
