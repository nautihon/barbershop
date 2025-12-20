@extends('layouts.app')

@section('title', 'Quản lý doanh thu')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý doanh thu tháng</h2>
        <div>
            <a href="{{ route('admin.revenues.export', ['year' => $year, 'month' => $month]) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Xuất Excel
            </a>
            @if(!$monthlyRevenue->is_closed)
            <form action="{{ route('admin.revenues.close-month') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn kết thúc tháng này? Doanh thu sẽ được xuất ra Excel và reset về 0.')">
                    <i class="bi bi-x-circle"></i> Kết thúc tháng
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <form method="GET" action="{{ route('admin.revenues.index') }}">
                <div class="mb-3">
                    <label for="year" class="form-label">Năm</label>
                    <select class="form-select" id="year" name="year" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
        <div class="col-md-3">
            <form method="GET" action="{{ route('admin.revenues.index') }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <div class="mb-3">
                    <label for="month" class="form-label">Tháng</label>
                    <select class="form-select" id="month" name="month" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Tổng doanh thu</h5>
                    <h3 class="text-primary">{{ number_format($totalRevenue) }} VNĐ</h3>
                    @if($monthlyRevenue->is_closed)
                        <span class="badge bg-secondary">Đã kết thúc</span>
                    @else
                        <span class="badge bg-success">Đang cập nhật</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu đơn hàng</h5>
                    <h3 class="text-info">{{ number_format($orderRevenue) }} VNĐ</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu dịch vụ</h5>
                    <h3 class="text-success">{{ number_format($appointmentRevenue) }} VNĐ</h3>
                </div>
            </div>
        </div>
    </div>

    @if($monthlyRevenue->is_closed)
    <div class="alert alert-warning mt-3">
        <strong>Lưu ý:</strong> Tháng này đã được kết thúc vào {{ $monthlyRevenue->closed_at->format('d/m/Y H:i') }}. 
        Doanh thu đã được xuất ra Excel và reset về 0 cho tháng mới.
    </div>
    @endif

    @if($closedMonths->count() > 0)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Lịch sử các tháng đã kết thúc</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tháng/Năm</th>
                        <th>Tổng doanh thu</th>
                        <th>Doanh thu đơn hàng</th>
                        <th>Doanh thu dịch vụ</th>
                        <th>Ngày kết thúc</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($closedMonths as $closed)
                    <tr>
                        <td>Tháng {{ $closed->month }}/{{ $closed->year }}</td>
                        <td>{{ number_format($closed->revenue) }} VNĐ</td>
                        <td>{{ number_format($closed->order_revenue) }} VNĐ</td>
                        <td>{{ number_format($closed->appointment_revenue) }} VNĐ</td>
                        <td>{{ $closed->closed_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.revenues.export', ['year' => $closed->year, 'month' => $closed->month]) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i> Tải lại Excel
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

