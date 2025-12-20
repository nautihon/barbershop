@extends('layouts.app')

@section('title', 'Chi tiết đơn xin nghỉ')

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h4 class="mb-0"><i class="bi bi-calendar-x"></i> Chi tiết đơn xin nghỉ</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Ngày bắt đầu:</strong> {{ $leaveRequest->start_date->format('d/m/Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Ngày kết thúc:</strong> {{ $leaveRequest->end_date->format('d/m/Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Số ngày nghỉ:</strong> {{ $leaveRequest->start_date->diffInDays($leaveRequest->end_date) + 1 }} ngày
                    </div>
                    <div class="mb-3">
                        <strong>Lý do:</strong>
                        <p class="mt-2">{{ $leaveRequest->reason ?? 'Không có' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $leaveRequest->status == 'approved' ? 'success' : ($leaveRequest->status == 'rejected' ? 'danger' : 'warning') }} ms-2">
                            @if($leaveRequest->status == 'pending') Chờ duyệt
                            @elseif($leaveRequest->status == 'approved') Đã duyệt
                            @else Từ chối
                            @endif
                        </span>
                    </div>
                    @if($leaveRequest->admin_note)
                        <div class="mb-3">
                            <strong>Ghi chú từ Admin:</strong>
                            <p class="mt-2">{{ $leaveRequest->admin_note }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>Ngày tạo:</strong> {{ $leaveRequest->created_at->format('d/m/Y H:i') }}
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('staff.leave-requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                        @if($leaveRequest->status == 'pending')
                            <form action="{{ route('staff.leave-requests.destroy', $leaveRequest) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn xin nghỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Xóa đơn
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

