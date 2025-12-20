@extends('layouts.app')

@section('title', 'Chi tiết nhân viên')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết nhân viên</h2>
        <div>
            <a href="{{ route('admin.staffs.edit', $staff) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <a href="{{ route('admin.staffs.schedule', $staff) }}" class="btn btn-primary">
                <i class="bi bi-calendar"></i> Lịch làm việc
            </a>
            <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($staff->avatar)
                        <img src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->name }}" class="img-fluid rounded-circle" style="max-width: 200px;">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 10rem; color: #ccc;"></i>
                    @endif
                    <h3 class="mt-3">{{ $staff->name }}</h3>
                    <p class="text-muted">{{ $staff->email }}</p>
                    <span class="badge bg-{{ $staff->status == 'active' ? 'success' : 'secondary' }}">
                        {{ $staff->status == 'active' ? 'Hoạt động' : 'Tạm dừng' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="200">Số điện thoại:</th>
                            <td>{{ $staff->phone }}</td>
                        </tr>
                        <tr>
                            <th>Chuyên môn:</th>
                            <td>{{ $staff->specialization ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Tiểu sử:</th>
                            <td>{{ $staff->bio ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dịch vụ:</th>
                            <td>
                                @if($staff->services->count() > 0)
                                    @foreach($staff->services as $service)
                                        <span class="badge bg-primary">{{ $service->name }}</span>
                                    @endforeach
                                @else
                                    Chưa được gán dịch vụ
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Lịch hẹn gần đây</h5>
                </div>
                <div class="card-body">
                    @if($staff->appointments->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Khách hàng</th>
                                    <th>Dịch vụ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staff->appointments->take(10) as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>{{ $appointment->service->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Chưa có lịch hẹn nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

