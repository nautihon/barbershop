@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                    @else
                        <i class="bi bi-image" style="font-size: 10rem; color: #ccc;"></i>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">{{ $product->description }}</p>
            
            <div class="mb-3">
                <h3 class="text-primary">{{ number_format($product->price) }} VNĐ</h3>
            </div>
            
            <table class="table">
                <tr>
                    <th width="150">Tồn kho:</th>
                    <td>
                        <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                            {{ $product->stock > 0 ? $product->stock . ' sản phẩm' : 'Hết hàng' }}
                        </span>
                    </td>
                </tr>
                @if($product->category)
                <tr>
                    <th>Danh mục:</th>
                    <td>{{ $product->category }}</td>
                </tr>
                @endif
            </table>
            
            @if($product->stock > 0 && $product->is_active)
                <form action="{{ route('user.cart.add') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Số lượng</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" required>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <button class="btn btn-secondary btn-lg w-100 mt-4" disabled>
                    Sản phẩm không khả dụng
                </button>
            @endif
            
            <div class="mt-3">
                <a href="{{ route('user.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

