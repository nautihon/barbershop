@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container mt-4">
    <h2>Thông tin cá nhân</h2>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h4>Điểm tích lũy</h4>
                    <h2 class="text-primary">{{ number_format($user->loyalty_points) }}</h2>
                    <p class="text-muted">1 điểm = 100 VNĐ</p>
                    <div class="alert alert-info">
                        <small>Bạn có thể sử dụng điểm tích lũy để giảm giá khi thanh toán đơn hàng hoặc dịch vụ.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="150">Tên:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Lịch sử tích điểm</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#orders">Từ đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#appointments">Từ dịch vụ</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3">
                        <div id="orders" class="tab-pane fade show active">
                            @if($orders->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Đơn hàng</th>
                                            <th>Điểm tích</th>
                                            <th>Điểm sử dụng</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td>#{{ $order->id }}</td>
                                            <td class="text-success">
                                                @if($order->loyalty_points_earned > 0)
                                                    +{{ number_format($order->loyalty_points_earned) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-danger">
                                                @if($order->loyalty_points_used > 0)
                                                    -{{ number_format($order->loyalty_points_used) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Chưa có lịch sử tích điểm từ đơn hàng.</p>
                            @endif
                        </div>
                        
                        <div id="appointments" class="tab-pane fade">
                            @if($appointments->count() > 0)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Dịch vụ</th>
                                            <th>Thợ</th>
                                            <th>Điểm tích</th>
                                            <th>Điểm sử dụng</th>
                                            <th>Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $appointment->service->name }}</td>
                                            <td>{{ $appointment->staff->name }}</td>
                                            <td class="text-success">
                                                @if($appointment->loyalty_points_earned > 0)
                                                    +{{ number_format($appointment->loyalty_points_earned) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-danger">
                                                @if($appointment->loyalty_points_used > 0)
                                                    -{{ number_format($appointment->loyalty_points_used) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-muted">Chưa có lịch sử tích điểm từ dịch vụ.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

