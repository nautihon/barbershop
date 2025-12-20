@extends('layouts.app')

@section('title', 'Quản lý lịch hẹn')

@section('content')
<div class="container-fluid mt-4">
    <h2>Quản lý lịch hẹn</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Thợ</th>
                        <th>Dịch vụ</th>
                        <th>Ngày & Giờ</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->user->name }}</td>
                        <td>{{ $appointment->staff->name }}</td>
                        <td>{{ $appointment->service->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                            {{ $appointment->appointment_time }}
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
                        <td>
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($appointment->status == 'pending')
                                <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Xác nhận
                                    </button>
                                </form>
                            @endif
                            @if($appointment->status != 'completed' && $appointment->status != 'cancelled')
                                <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy?')">
                                        <i class="bi bi-x-circle"></i> Hủy
                                    </button>
                                </form>
                            @endif
                            @if($appointment->status == 'completed')
                                <a href="{{ route('admin.appointments.invoice', $appointment) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-receipt"></i> Hóa đơn
                                </a>
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

