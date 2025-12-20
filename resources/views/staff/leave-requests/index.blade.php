@extends('layouts.app')

@section('title', 'Đơn xin nghỉ của tôi')

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-x"></i> Đơn xin nghỉ của tôi</h2>
        <a href="{{ route('staff.leave-requests.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tạo đơn xin nghỉ mới
        </a>
    </div>
    
    @if($leaveRequests->count() > 0)
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
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
                                <a href="{{ route('staff.leave-requests.show', $request) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($request->status == 'pending')
                                    <form action="{{ route('staff.leave-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn xin nghỉ này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Bạn chưa có đơn xin nghỉ nào.</p>
                <a href="{{ route('staff.leave-requests.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tạo đơn xin nghỉ đầu tiên
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

