@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h2>Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tổng lịch hẹn</h5>
                    <h2>{{ $stats['total_appointments'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Lịch chờ xác nhận</h5>
                    <h2>{{ $stats['pending_appointments'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.revenues.index') }}" class="text-decoration-none">
                <div class="card text-white bg-success" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <div class="card-body">
                        <h5 class="card-title">Doanh thu tháng</h5>
                        <h2>{{ number_format($stats['monthly_revenue']) }} VNĐ</h2>
                        <small>Xem chi tiết <i class="bi bi-arrow-right"></i></small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Tổng khách hàng</h5>
                    <h2>{{ $stats['total_customers'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #6c757d;">
                <div class="card-body">
                    <h5 class="card-title">Đơn xin nghỉ chờ duyệt</h5>
                    <h2>{{ $stats['pending_leave_requests'] }}</h2>
                    @if($stats['pending_leave_requests'] > 0)
                        <a href="{{ route('admin.leave-requests.index') }}" class="text-white text-decoration-none">
                            <small>Xem chi tiết <i class="bi bi-arrow-right"></i></small>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Tổng đơn xin nghỉ</h5>
                    <h2>{{ $stats['total_leave_requests'] }}</h2>
                    <a href="{{ route('admin.leave-requests.index') }}" class="text-white text-decoration-none">
                        <small>Xem tất cả <i class="bi bi-arrow-right"></i></small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #17a2b8;">
                <div class="card-body">
                    <h5 class="card-title">Tổng đơn hàng</h5>
                    <h2>{{ $stats['total_orders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #fd7e14;">
                <div class="card-body">
                    <h5 class="card-title">Tổng nhân viên</h5>
                    <h2>{{ $stats['total_staff'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lịch hẹn hôm nay</h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_appointments->count() > 0)
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <style>
                                /* Custom scrollbar styling */
                                .table-responsive::-webkit-scrollbar {
                                    width: 8px;
                                }
                                
                                .table-responsive::-webkit-scrollbar-track {
                                    background: #f1f1f1;
                                    border-radius: 10px;
                                }
                                
                                .table-responsive::-webkit-scrollbar-thumb {
                                    background: #888;
                                    border-radius: 10px;
                                }
                                
                                .table-responsive::-webkit-scrollbar-thumb:hover {
                                    background: #555;
                                }
                                
                                /* Sticky header styling */
                                .table-responsive table thead {
                                    position: sticky;
                                    top: 0;
                                    z-index: 10;
                                    background-color: #f8f9fa;
                                }
                                
                                .table-responsive table thead th {
                                    background-color: #f8f9fa;
                                    border-bottom: 2px solid #dee2e6;
                                    padding: 12px;
                                    font-weight: 600;
                                    color: #495057;
                                }
                                
                                .table-responsive table tbody td {
                                    padding: 10px 12px;
                                    vertical-align: middle;
                                }
                                
                                .table-responsive table tbody tr:hover {
                                    background-color: #f8f9fa;
                                }
                            </style>
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="min-width: 120px;">Khách hàng</th>
                                        <th style="min-width: 100px;">Thợ</th>
                                        <th style="min-width: 120px;">Dịch vụ</th>
                                        <th style="min-width: 120px;">Ngày</th>
                                        <th style="min-width: 100px;">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->user->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->staff->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                        <td>
                                            <small>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }} 
                                                @if($appointment->appointment_time)
                                                    <br>{{ $appointment->appointment_time }}
                                                @else
                                                    <br>N/A
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'secondary')) }}">
                                                @if($appointment->status == 'pending') Chờ xác nhận
                                                @elseif($appointment->status == 'confirmed') Đã xác nhận
                                                @elseif($appointment->status == 'completed') Hoàn thành
                                                @else Đã hủy
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4">
                            <p class="text-muted text-center mb-0">Không có lịch hẹn nào trong ngày hôm nay</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Dịch vụ phổ biến</h5>
                </div>
                <div class="card-body">
                    @if($popular_services->count() > 0)
                        <ul class="list-group">
                            @foreach($popular_services as $service)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $service->name }}</span>
                                <span class="badge bg-primary">{{ $service->appointments_count }} lịch</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Chưa có dữ liệu</p>
                    @endif
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Thợ được chọn nhiều nhất</h5>
                </div>
                <div class="card-body">
                    @if($popular_staff->count() > 0)
                        <ul class="list-group">
                            @foreach($popular_staff as $staff)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>{{ $staff->name }}</span>
                                <span class="badge bg-primary">{{ $staff->appointments_count }} lịch</span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Chưa có dữ liệu</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn hàng hôm nay</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @if($recent_orders->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total_amount - ($order->discount_amount ?? 0)) }} VNĐ</td>
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center">Không có đơn hàng nào trong ngày hôm nay</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn xin nghỉ hôm nay</h5>
                    <a href="{{ route('admin.leave-requests.index') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    @if($recent_leave_requests->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Thợ</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_leave_requests as $request)
                                <tr>
                                    <td>{{ $request->staff->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                            @if($request->status == 'pending') Chờ duyệt
                                            @elseif($request->status == 'approved') Đã duyệt
                                            @else Từ chối
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.leave-requests.show', $request) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center">Không có đơn xin nghỉ nào trong ngày hôm nay</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

