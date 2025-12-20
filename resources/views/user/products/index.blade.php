@extends('layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container mt-4">
    <h2>Sản phẩm chăm sóc tóc</h2>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                    <p class="card-text">
                        <strong>Giá:</strong> {{ number_format($product->price) }} VNĐ<br>
                        <strong>Tồn kho:</strong> {{ $product->stock }} sản phẩm
                    </p>
                    <a href="{{ route('user.products.show', $product) }}" class="btn btn-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{ $products->links() }}
</div>
@endsection

