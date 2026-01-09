@extends('layouts.app')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết lịch hẹn #{{ $appointment->id }}</h2>
        <a href="{{ route('user.appointments.index') }}" class="btn btn-secondary">Quay lại</a>
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
                            <th width="200">Thợ:</th>
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
                    
                    @if($appointment->status == 'pending')
                        <div class="mt-3">
                            <!-- <a href="{{ route('user.appointments.index') }}" class="btn btn-primary">Chỉnh sửa</a>
                            <form action="{{ route('user.appointments.destroy', $appointment) }}" method="POST" class="d-inline"> -->
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn?')">
                                    Hủy lịch hẹn
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if($appointment->status == 'completed' && !$appointment->review)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Đánh giá dịch vụ</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Đánh giá (1-5 sao)</label>
                                        <select name="rating" class="form-select" required>
                                            <option value="">Chọn điểm</option>
                                            <option value="5">5 sao - Tuyệt vời</option>
                                            <option value="4">4 sao - Tốt</option>
                                            <option value="3">3 sao - Bình thường</option>
                                            <option value="2">2 sao - Không hài lòng</option>
                                            <option value="1">1 sao - Rất không hài lòng</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nhận xét</label>
                                        <textarea name="comment" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                </form>
                            </div>
                        </div>
                    @endif
                    
                    @if($appointment->review)
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Đánh giá của bạn</h5>
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
    </div>
</div>
@endsection

