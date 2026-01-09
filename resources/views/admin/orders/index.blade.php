@extends('layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý đơn hàng</h2>
    </div>
    
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Lọc theo ngày</label>
                    <input type="date" 
                           class="form-control" 
                           id="date" 
                           name="date" 
                           value="{{ $selectedDate }}"
                           required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Hôm nay
                    </a>
                </div>
                <div class="col-md-4 text-end">
                    <span class="text-muted">
                        <i class="bi bi-calendar-check"></i> 
                        Đang hiển thị: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                    </span>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
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
                        <td>{{ $order->user->name }}</td>
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
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

