@extends('layouts.app')

@section('title', 'Lịch hẹn của tôi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lịch hẹn của tôi</h2>
        <a href="{{ route('user.appointments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Đặt lịch mới
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($appointments->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dịch vụ</th>
                            <th>Thợ</th>
                            <th>Ngày & Giờ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->service->name }}</td>
                            <td>{{ $appointment->staff->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }} {{ $appointment->appointment_time }}</td>
                            <td>
                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'secondary')) }}">
                                    @if($appointment->status == 'pending') Chờ xác nhận
                                    @elseif($appointment->status == 'confirmed') Đã xác nhận
                                    @elseif($appointment->status == 'completed') Hoàn thành
                                    @else Đã hủy
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('user.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($appointment->status == 'pending')
                                    <form action="{{ route('user.appointments.destroy', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $appointments->links() }}
            @else
                <p class="text-center text-muted">Bạn chưa có lịch hẹn nào. <a href="{{ route('user.appointments.create') }}">Đặt lịch ngay</a></p>
            @endif
        </div>
    </div>
</div>
@endsection

