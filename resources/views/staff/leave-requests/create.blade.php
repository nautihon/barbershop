@extends('layouts.app')

@section('title', 'Tạo đơn xin nghỉ')

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h4 class="mb-0"><i class="bi bi-calendar-x"></i> Tạo đơn xin nghỉ</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.leave-requests.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Ngày bắt đầu nghỉ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Ngày kết thúc nghỉ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do nghỉ</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Gửi đơn
                            </button>
                            <a href="{{ route('staff.leave-requests.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
</script>
@endsection

