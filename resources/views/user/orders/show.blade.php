@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>
        <a href="{{ route('user.orders.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Chi tiết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderItems as $item)
                            <tr>
                                <td>
                                    @if($item->product)
                                        {{ $item->product->name }}
                                    @else
                                        <span class="text-muted">Sản phẩm đã bị xóa</span>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }} VNĐ</td>
                                <td>{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Không có sản phẩm nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th>{{ number_format($order->total_amount) }} VNĐ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Trạng thái:</strong><br>
                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                            @if($order->status == 'pending') Chờ xác nhận
                            @elseif($order->status == 'confirmed') Đã xác nhận
                            @elseif($order->status == 'processing') Đang xử lý
                            @elseif($order->status == 'shipped') Đã giao hàng
                            @elseif($order->status == 'delivered') Đã nhận hàng
                            @else Đã hủy
                            @endif
                        </span>
                    </p>
                    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
                    @if($order->notes)
                        <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                    @endif
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

