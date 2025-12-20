@extends('layouts.app')

@section('title', 'Staff Dashboard')

@section('content')
<div class="container-fluid mt-4">
    <h2>Dashboard - {{ $staff->name }}</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Lịch hôm nay</h5>
                    <h2>{{ $stats['today_count'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Chờ xác nhận</h5>
                    <h2>{{ $stats['pending_count'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Đã hoàn thành</h5>
                    <h2>{{ $stats['completed_count'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Lịch hẹn hôm nay</h5>
                </div>
                <div class="card-body">
                    @if($todayAppointments->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Giờ</th>
                                    <th>Khách hàng</th>
                                    <th>Dịch vụ</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_time }}</td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>{{ $appointment->service->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ $appointment->status == 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('staff.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Không có lịch hẹn nào hôm nay.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Lịch hẹn sắp tới</h5>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Giờ</th>
                                    <th>Khách hàng</th>
                                    <th>Dịch vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->appointment_time }}</td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>{{ $appointment->service->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Không có lịch hẹn sắp tới.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

