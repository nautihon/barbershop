@extends('layouts.app')

@section('title', 'Sửa kiểu tóc nổi bật')

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h4 class="mb-0"><i class="bi bi-pencil"></i> Sửa kiểu tóc nổi bật</h4>
                </div>
                <div class="card-body">
                    @if($hairstyle->image)
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh hiện tại</label>
                            <div>
                                <img src="{{ asset('storage/' . $hairstyle->image) }}" alt="{{ $hairstyle->title }}" class="img-fluid" style="max-height: 300px; border-radius: 8px;">
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route('staff.hairstyles.update', $hairstyle) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $hairstyle->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $hairstyle->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh mới (để trống nếu không thay đổi)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Định dạng: JPG, PNG. Tối đa 2MB</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật
                            </button>
                            <a href="{{ route('staff.hairstyles.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

