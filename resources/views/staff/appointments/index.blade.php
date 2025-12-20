@extends('layouts.app')

@section('title', 'Lịch làm việc')

@section('content')
<div class="container-fluid mt-4">
    <h2>Lịch làm việc - {{ $staff->name }}</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.appointments.index') }}" class="row g-3 mb-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('staff.appointments.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ</th>
                        <th>Khách hàng</th>
                        <th>Dịch vụ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                        <td>{{ $appointment->appointment_time }}</td>
                        <td>{{ $appointment->user->name }}</td>
                        <td>{{ $appointment->service->name }}</td>
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
                            <a href="{{ route('staff.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($appointment->status == 'confirmed')
                                <form action="{{ route('staff.appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Đánh dấu lịch hẹn đã hoàn thành?')">
                                        <i class="bi bi-check-circle"></i> Hoàn thành
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection

