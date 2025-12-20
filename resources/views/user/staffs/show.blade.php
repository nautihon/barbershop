@extends('layouts.app')

@section('title', 'Chi tiết thợ - ' . $staff->name)

@section('content')
<div class="container" style="padding: 80px 0;">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($staff->avatar)
                        <img src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->name }}" class="img-fluid rounded-circle mb-3" style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                            <i class="bi bi-person-circle" style="font-size: 8rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <h3>{{ $staff->name }}</h3>
                    <p class="text-muted mb-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <strong>Chuyên môn:</strong> {{ $staff->specialization }}
                    </p>
                    @if($staff->bio)
                        <p class="text-muted">{{ $staff->bio }}</p>
                    @endif
                    <div class="mt-3">
                        <p class="mb-1"><strong>Email:</strong> {{ $staff->email }}</p>
                        <p class="mb-0"><strong>SĐT:</strong> {{ $staff->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Services Section -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h5 class="mb-0"><i class="bi bi-scissors"></i> Dịch vụ đảm nhận</h5>
                </div>
                <div class="card-body">
                    @if($staff->services->count() > 0)
                        <div class="row">
                            @foreach($staff->services as $service)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <div>
                                        <strong>{{ $service->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ number_format($service->price) }} VNĐ - {{ $service->duration }} phút</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Thợ này chưa được gán dịch vụ nào.</p>
                    @endif
                </div>
            </div>
            
            <!-- Hairstyles Section -->
            <div class="card mb-4">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h5 class="mb-0"><i class="bi bi-image"></i> Kiểu tóc nổi bật</h5>
                </div>
                <div class="card-body">
                    @if($staff->hairstyles->count() > 0)
                        <div class="row">
                            @foreach($staff->hairstyles as $hairstyle)
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    @if($hairstyle->image)
                                        <img src="{{ asset('storage/' . $hairstyle->image) }}" class="card-img-top" alt="{{ $hairstyle->title }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $hairstyle->title }}</h6>
                                        @if($hairstyle->description)
                                            <p class="card-text text-muted small">{{ Str::limit($hairstyle->description, 100) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Thợ này chưa có kiểu tóc nổi bật nào.</p>
                    @endif
                </div>
            </div>
            
            <!-- Reviews Section -->
            @if($staff->reviews->count() > 0)
            <div class="card">
                <div class="card-header" style="background-color: var(--primary-color); color: var(--text-light);">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Đánh giá</h5>
                </div>
                <div class="card-body">
                    @foreach($staff->reviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $review->user->name }}</strong>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('user.home') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        @auth
            @if(auth()->user()->isUser())
                <a href="{{ route('user.appointments.create', ['staff_id' => $staff->id]) }}" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i> Đặt lịch với thợ này
                </a>
            @endif
        @endauth
    </div>
</div>
@endsection

