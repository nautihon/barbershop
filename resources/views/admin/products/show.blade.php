@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết sản phẩm</h2>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $product->name }}</h3>
                    <p class="text-muted">{{ $product->description }}</p>
                    <table class="table">
                        <tr>
                            <th width="200">Giá:</th>
                            <td><strong>{{ number_format($product->price) }} VNĐ</strong></td>
                        </tr>
                        <tr>
                            <th>Tồn kho:</th>
                            <td>
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock }} sản phẩm
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Danh mục:</th>
                            <td>{{ $product->category ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                    {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

