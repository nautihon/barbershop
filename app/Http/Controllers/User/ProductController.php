<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0);
        
        // Filter by category if not 'all'
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        
        $products = $query->latest()->paginate(12)->withQueryString();
        
        // Get all categories for filter tabs
        $categories = [
            'all' => 'Tất cả',
            'Sáp vuốt tóc' => 'Sáp vuốt tóc',
            'Pre-styling' => 'Pre-styling',
            'Chăm sóc tóc' => 'Chăm sóc tóc',
        ];
        
        // Count products per category for badges
        $categoryCounts = [];
        foreach ($categories as $key => $label) {
            if ($key === 'all') {
                $categoryCounts[$key] = Product::where('is_active', true)
                    ->where('stock', '>', 0)
                    ->count();
            } else {
                $categoryCounts[$key] = Product::where('is_active', true)
                    ->where('stock', '>', 0)
                    ->where('category', $key)
                    ->count();
            }
        }
        
        return view('user.products.index', compact('products', 'categories', 'category', 'categoryCounts'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            abort(404);
        }
        
        return view('user.products.show', compact('product'));
    }
}
