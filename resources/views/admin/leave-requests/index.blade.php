@extends('layouts.app')

@section('title', 'Quản lý đơn xin nghỉ')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-x"></i> Quản lý đơn xin nghỉ</h2>
    </div>
    
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.leave-requests.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="date" class="form-label">Lọc theo ngày</label>
                    <input type="date" 
                           class="form-control" 
                           id="date" 
                           name="date" 
                           value="{{ $selectedDate }}"
                           required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    <a href="{{ route('admin.leave-requests.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Hôm nay
                    </a>
                </div>
                <div class="col-md-4 text-end">
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
            @if($leaveRequests->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Thợ</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Số ngày</th>
                            <th>Lý do</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveRequests as $request)
                        <tr>
                            <td>{{ $request->staff->name }}</td>
                            <td>{{ $request->start_date->format('d/m/Y') }}</td>
                            <td>{{ $request->end_date->format('d/m/Y') }}</td>
                            <td>{{ $request->start_date->diffInDays($request->end_date) + 1 }} ngày</td>
                            <td>{{ Str::limit($request->reason ?? 'Không có', 50) }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'rejected' ? 'danger' : 'warning') }}">
                                    @if($request->status == 'pending') Chờ duyệt
                                    @elseif($request->status == 'approved') Đã duyệt
                                    @else Từ chối
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.leave-requests.show', $request) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $leaveRequests->links() }}
            @else
                <p class="text-center text-muted">Chưa có đơn xin nghỉ nào.</p>
            @endif
        </div>
    </div>
</div>
@endsection

