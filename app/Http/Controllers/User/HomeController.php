<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->latest()->take(6)->get();
        $staffs = Staff::with('services')->latest()->get();
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();
        
        return view('user.home', compact('services', 'staffs', 'products'));
    }
}
