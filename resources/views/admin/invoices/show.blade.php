@extends('layouts.app')

@section('title', 'Hóa đơn')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-body">
            <div class="text-end mb-3">
                <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-secondary">Quay lại</a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> In hóa đơn
                </button>
            </div>
            
            <div class="invoice" id="invoice">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3>BARBERSHOP</h3>
                        <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                        <p>Điện thoại: 0123456789</p>
                        <p>Email: info@barbershop.com</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h4>HÓA ĐƠN DỊCH VỤ</h4>
                        <p><strong>Mã hóa đơn:</strong> #{{ $appointment->id }}</p>
                        <p><strong>Ngày:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Thông tin khách hàng:</h5>
                        <p><strong>Tên:</strong> {{ $appointment->user->name }}</p>
                        <p><strong>Email:</strong> {{ $appointment->user->email }}</p>
                        <p><strong>SĐT:</strong> {{ $appointment->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Thông tin dịch vụ:</h5>
                        <p><strong>Thợ:</strong> {{ $appointment->staff->name }}</p>
                        <p><strong>Dịch vụ:</strong> {{ $appointment->service->name }}</p>
                        <p><strong>Ngày thực hiện:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</p>
                        <p><strong>Giờ:</strong> {{ $appointment->appointment_time }}</p>
                    </div>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Dịch vụ</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>{{ $appointment->service->name }}</td>
                            <td class="text-end">{{ number_format($appointment->service->price) }} VNĐ</td>
                            <td class="text-end"><strong>{{ number_format($appointment->service->price) }} VNĐ</strong></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        @if($appointment->loyalty_points_used > 0)
                        <tr>
                            <td colspan="3" class="text-end">Giảm giá ({{ $appointment->loyalty_points_used }} điểm):</td>
                            <td class="text-end text-danger">-{{ number_format($appointment->discount_amount) }} VNĐ</td>
                        </tr>
                        @endif
                        <tr>
                            <th colspan="3" class="text-end">Tổng cộng:</th>
                            <th class="text-end">{{ number_format($appointment->service->price - ($appointment->discount_amount ?? 0)) }} VNĐ</th>
                        </tr>
                    </tfoot>
                </table>

                @if($appointment->status == 'completed' && $appointment->loyalty_points_used == 0 && $appointment->user->loyalty_points > 0)
                <div class="alert alert-info mt-3">
                    <h6>Sử dụng điểm tích lũy</h6>
                    <p>Khách hàng có {{ number_format($appointment->user->loyalty_points) }} điểm tích lũy (1 điểm = 100 VNĐ)</p>
                    <form action="{{ route('admin.appointments.invoice.use-loyalty-points', $appointment) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="input-group mb-2">
                            <input type="number" name="loyalty_points" class="form-control" 
                                   value="{{ min($appointment->user->loyalty_points, intval($appointment->service->price / 100)) }}" 
                                   min="0" max="{{ $appointment->user->loyalty_points }}" required>
                            <button type="submit" class="btn btn-primary">Sử dụng điểm</button>
                        </div>
                    </form>
                    <small class="text-muted">Nếu không sử dụng, điểm sẽ được tích vào tài khoản khách hàng.</small>
                </div>
                @endif
                
                <div class="mt-4">
                    <p><strong>Ghi chú:</strong> {{ $appointment->notes ?? 'Không có' }}</p>
                    <p class="text-muted">Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, nav, .card-header {
        display: none !important;
    }
    .invoice {
        border: none;
    }
}
</style>
@endsection

