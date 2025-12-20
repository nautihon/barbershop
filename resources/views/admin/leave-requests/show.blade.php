@extends('layouts.app')

@section('title', 'Chi tiết đơn xin nghỉ')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-x"></i> Chi tiết đơn xin nghỉ</h2>
        <a href="{{ route('admin.leave-requests.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h5 class="mb-0">Thông tin đơn xin nghỉ</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Thợ:</strong> {{ $leaveRequest->staff->name }}
                    </div>
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
                            <strong>Ghi chú của Admin:</strong>
                            <p class="mt-2">{{ $leaveRequest->admin_note }}</p>
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>Ngày tạo:</strong> {{ $leaveRequest->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            @if($leaveRequest->status == 'pending')
                <div class="card">
                    <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                        <h5 class="mb-0">Duyệt đơn</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.leave-requests.approve', $leaveRequest) }}" method="POST" class="mb-3">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="admin_note_approve" class="form-label">Ghi chú (tùy chọn)</label>
                                <textarea class="form-control" id="admin_note_approve" name="admin_note" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Duyệt đơn
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.leave-requests.reject', $leaveRequest) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="admin_note_reject" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('admin_note') is-invalid @enderror" id="admin_note_reject" name="admin_note" rows="3" required></textarea>
                                @error('admin_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle"></i> Từ chối
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

