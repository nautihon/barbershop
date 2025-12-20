@extends('layouts.app')

@section('title', 'Thống kê')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Thống kê & Báo cáo</h2>
        <form method="GET" action="{{ route('admin.statistics.index') }}" class="d-inline">
            <select name="period" class="form-select d-inline" style="width: auto;" onchange="this.form.submit()">
                <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Hôm nay</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Tuần này</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Tháng này</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Năm nay</option>
            </select>
        </form>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu ({{ $period == 'day' ? 'Hôm nay' : ($period == 'week' ? 'Tuần này' : ($period == 'month' ? 'Tháng này' : 'Năm nay')) }})</h5>
                    <h2>{{ number_format($revenue) }} VNĐ</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tổng lịch hẹn</h5>
                    <h2>{{ $appointments['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Tổng đơn hàng</h5>
                    <h2>{{ $orders['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Khách hàng mới</h5>
                    <h2>{{ $newCustomers }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Thống kê lịch hẹn</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Chờ xác nhận:</td>
                            <td><strong>{{ $appointments['pending'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Đã xác nhận:</td>
                            <td><strong>{{ $appointments['confirmed'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Hoàn thành:</td>
                            <td><strong>{{ $appointments['completed'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Đã hủy:</td>
                            <td><strong>{{ $appointments['cancelled'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Thống kê đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Chờ xác nhận:</td>
                            <td><strong>{{ $orders['pending'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Đã xác nhận:</td>
                            <td><strong>{{ $orders['confirmed'] }}</strong></td>
                        </tr>
                        <tr>
                            <td>Đã nhận hàng:</td>
                            <td><strong>{{ $orders['delivered'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Dịch vụ phổ biến</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($popularServices as $service)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $service->name }}</span>
                            <span class="badge bg-primary">{{ $service->appointments_count }} lịch</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Thợ được chọn nhiều nhất</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($popularStaff as $staff)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $staff->name }}</span>
                            <span class="badge bg-primary">{{ $staff->appointments_count }} lịch</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

