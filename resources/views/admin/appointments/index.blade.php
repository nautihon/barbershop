@extends('layouts.app')

@section('title', 'Quản lý lịch hẹn')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý lịch hẹn</h2>
    </div>
    
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="date" class="form-label">Lọc theo ngày</label>
                    <input type="date" 
                           class="form-control" 
                           id="date" 
                           name="date" 
                           value="{{ $selectedDate }}"
                           required>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Lọc theo trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Hôm nay
                    </a>
                </div>
                <div class="col-md-3 text-end">
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
                            <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($appointment->status == 'pending')
                                <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" title="Xác nhận">
                                        <i class="bi bi-check-circle"></i> Xác nhận
                                    </button>
                                </form>
                            @endif
                            @if($appointment->status != 'completed' && $appointment->status != 'cancelled')
                                <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Bạn có chắc chắn muốn hủy?')" title="Hủy lịch hẹn">
                                        <i class="bi bi-x-circle"></i> Hủy
                                    </button>
                                </form>
                            @endif
                            @if($appointment->status == 'completed')
                                <a href="{{ route('admin.appointments.invoice', $appointment) }}" class="btn btn-sm btn-primary" title="Xem hóa đơn">
                                    <i class="bi bi-receipt"></i> Hóa đơn
                                </a>
                            @endif
                            @if($appointment->status != 'completed')
                                <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này? Hành động này không thể hoàn tác.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa lịch hẹn">
                                        <i class="bi bi-trash"></i> Xóa
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

