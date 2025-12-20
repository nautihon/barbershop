@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mt-4">
    <h2>Đơn hàng của tôi</h2>
    
    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ number_format($order->total_amount) }} VNĐ</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                    @if($order->status == 'pending') Chờ xác nhận
                                    @elseif($order->status == 'confirmed') Đã xác nhận
                                    @elseif($order->status == 'processing') Đang xử lý
                                    @elseif($order->status == 'shipped') Đã giao hàng
                                    @elseif($order->status == 'delivered') Đã nhận hàng
                                    @else Đã hủy
                                    @endif
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('user.orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            @else
                <p class="text-center text-muted">Bạn chưa có đơn hàng nào. <a href="{{ route('user.products.index') }}">Mua sắm ngay</a></p>
            @endif
        </div>
    </div>
</div>
@endsection

