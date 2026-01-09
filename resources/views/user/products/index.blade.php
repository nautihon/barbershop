@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Sản phẩm chăm sóc tóc</h2>
    
    <!-- Category Filter Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills nav-justified" id="categoryTabs" role="tablist">
            @foreach($categories as $key => $label)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $category === $key ? 'active' : '' }}" 
                   href="{{ route('user.products.index', ['category' => $key]) }}"
                   style="cursor: pointer;">
                    {{ $label }}
                    @if($key !== 'all' && isset($categoryCounts[$key]) && $categoryCounts[$key] > 0)
                        <span class="badge bg-secondary ms-2">
                            {{ $categoryCounts[$key] }}
                        </span>
                    @endif
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-info text-dark">{{ $product->category ?? 'Chưa phân loại' }}</span>
                        </div>
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 100) }}</p>
                        <div class="mt-auto">
                            <p class="card-text mb-2">
                                <strong class="text-primary" style="font-size: 1.2rem;">{{ number_format($product->price) }} VNĐ</strong><br>
                                <small class="text-muted">
                                    <i class="bi bi-box-seam"></i> Còn lại: {{ $product->stock }} sản phẩm
                                </small>
                            </p>
                            <a href="{{ route('user.products.show', $product) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3">Không có sản phẩm nào trong danh mục này.</p>
            <a href="{{ route('user.products.index', ['category' => 'all']) }}" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left"></i> Xem tất cả sản phẩm
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .nav-pills .nav-link {
        color: #495057;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin: 0 5px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--primary-color, #1a1a1a);
        color: white;
        border-color: var(--primary-color, #1a1a1a);
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15) !important;
    }
    
    .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection

