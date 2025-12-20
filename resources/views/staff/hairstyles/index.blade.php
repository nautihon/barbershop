@extends('layouts.app')

@section('title', 'Quản lý kiểu tóc nổi bật')

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-image"></i> Kiểu tóc nổi bật của tôi</h2>
        <a href="{{ route('staff.hairstyles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm kiểu tóc mới
        </a>
    </div>
    
    @if($hairstyles->count() > 0)
        <div class="row">
            @foreach($hairstyles as $hairstyle)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($hairstyle->image)
                        <img src="{{ asset('storage/' . $hairstyle->image) }}" class="card-img-top" alt="{{ $hairstyle->title }}" style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $hairstyle->title }}</h5>
                        @if($hairstyle->description)
                            <p class="card-text text-muted">{{ Str::limit($hairstyle->description, 100) }}</p>
                        @endif
                        <div class="d-flex gap-2">
                            <a href="{{ route('staff.hairstyles.edit', $hairstyle) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <form action="{{ route('staff.hairstyles.destroy', $hairstyle) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Bạn chưa có kiểu tóc nổi bật nào.</p>
                <a href="{{ route('staff.hairstyles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm kiểu tóc đầu tiên
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

