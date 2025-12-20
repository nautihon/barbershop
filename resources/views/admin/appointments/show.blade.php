@extends('layouts.app')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết lịch hẹn #{{ $appointment->id }}</h2>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin lịch hẹn</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="200">Khách hàng:</th>
                            <td>{{ $appointment->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $appointment->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Thợ:</th>
                            <td>{{ $appointment->staff->name }}</td>
                        </tr>
                        <tr>
                            <th>Dịch vụ:</th>
                            <td>{{ $appointment->service->name }}</td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td>{{ number_format($appointment->service->price) }} VNĐ</td>
                        </tr>
                        <tr>
                            <th>Ngày hẹn:</th>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Giờ hẹn:</th>
                            <td>{{ $appointment->appointment_time }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
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
                        @if($appointment->notes)
                        <tr>
                            <th>Ghi chú:</th>
                            <td>{{ $appointment->notes }}</td>
                        </tr>
                        @endif
                    </table>
                    
                    <div class="mt-3">
                    @if($appointment->status == 'pending')
                        <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Xác nhận lịch hẹn
                            </button>
                        </form>
                    @endif
                    @if($appointment->status != 'completed' && $appointment->status != 'cancelled')
                        <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">
                                <i class="bi bi-x-circle"></i> Hủy lịch hẹn
                            </button>
                        </form>
                    @endif
                    @if($appointment->status == 'completed')
                        <a href="{{ route('admin.appointments.invoice', $appointment) }}" class="btn btn-primary">
                            <i class="bi bi-receipt"></i> Xem hóa đơn
                        </a>
                    @endif
                    </div>
                </div>
            </div>
            
            @if($appointment->review)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Đánh giá</h5>
                </div>
                <div class="card-body">
                    <p><strong>Điểm:</strong> 
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $appointment->review->rating ? '-fill text-warning' : '' }}"></i>
                        @endfor
                        ({{ $appointment->review->rating }}/5)
                    </p>
                    @if($appointment->review->comment)
                        <p><strong>Nhận xét:</strong> {{ $appointment->review->comment }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

