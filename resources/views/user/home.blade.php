@extends('layouts.app')

@section('title', 'Trang chủ - Barbershop')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1>Custom Barbering for the Modern Man</h1>
        <p>Expert grooming, premier service, and a shot of confidence. Welcome to Barbershop.</p>
        <div class="hero-cta">
            @auth
                @if(auth()->user()->isUser())
                    <a href="{{ route('user.appointments.create') }}" class="btn btn-primary">
                        <i class="bi bi-calendar-check"></i> Đặt lịch hẹn
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-calendar-check"></i> Đặt lịch hẹn
                </a>
            @endauth
            <a href="tel:+84123456789" class="btn btn-secondary">
                <i class="bi bi-telephone"></i> Gọi: 0987665918
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Custom hero background image - bạn có thể thay đổi URL hình ảnh */
    .hero-section {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(44, 44, 44, 0.85) 100%),
                    url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center center / cover no-repeat !important;
    }
    
    /* 
    Để sử dụng hình ảnh local từ storage:
    1. Upload hình ảnh vào storage/app/public/hero-background.jpg
    2. Bỏ comment dòng dưới và comment dòng trên
    3. Đảm bảo đã chạy: php artisan storage:link
    */
    /* .hero-section {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(44, 44, 44, 0.85) 100%),
                    url('{{ asset("storage/hero-background.jpg") }}') center center / cover no-repeat !important;
    } */
    
    /* Promotion Banner Styles */
    .promotion-banner {
        padding: 80px 0;
        background-color: var(--bg-light);
    }
    
    .promotion-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        padding: 3rem 2rem;
        text-align: center;
        border-top: 4px solid var(--secondary-color);
    }
    
    .promotion-icon {
        font-size: 3.5rem;
        color: var(--secondary-color);
        margin-bottom: 1.5rem;
    }
    
    .promotion-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }
    
    .promotion-description {
        font-size: 1.1rem;
        color: #666;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .promotion-features {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .promotion-feature {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background-color: var(--bg-light);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .promotion-feature:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: var(--secondary-color);
    }
    
    .promotion-feature i {
        font-size: 1.5rem;
        color: var(--secondary-color);
    }
    
    .promotion-feature strong {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .promotion-feature span {
        color: var(--text-dark);
    }
</style>
@endpush

<!-- Promotion Banner Section -->
<section class="promotion-banner">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="promotion-card">
                    <div class="promotion-icon">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <h2 class="promotion-title">Chương trình ưu đãi đặc biệt</h2>
                    <p class="promotion-description">
                        Mua hàng và sử dụng dịch vụ để tích điểm, đổi điểm lấy giảm giá cho các lần mua sau!
                    </p>
                    <div class="promotion-features">
                        <div class="promotion-feature">
                            <i class="bi bi-cart-check"></i>
                            <span><strong>Mua hàng</strong> tích điểm</span>
                        </div>
                        <div class="promotion-feature">
                            <i class="bi bi-scissors"></i>
                            <span><strong>Sử dụng dịch vụ</strong> tích điểm</span>
                        </div>
                        <div class="promotion-feature">
                            <i class="bi bi-percent"></i>
                            <span><strong>Đổi điểm</strong> lấy giảm giá</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="feature-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h3>Dịch vụ hoàn chỉnh</h3>
                    <p>Cắt tóc, cạo râu, massage da đầu và nhiều dịch vụ khác.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h3>Đặt lịch dễ dàng</h3>
                    <p>Đặt lịch trực tuyến một cách đơn giản và nhanh chóng.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h3>Không gian chuyên nghiệp</h3>
                    <p>Thư giãn, đọc báo hoặc xem thể thao trong không gian sang trọng.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section style="padding: 80px 0;">
    <div class="container">
        <div class="section-title">
            <h2>Dịch vụ của chúng tôi</h2>
            <p>Come in for the haircut, walk out on top of your game</p>
        </div>
        
        <div class="row">
            @forelse($services as $service)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top" alt="{{ $service->name }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="bi bi-scissors" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($service->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary fw-bold">{{ number_format($service->price) }} VNĐ</span>
                            <span class="text-muted"><i class="bi bi-clock"></i> {{ $service->duration }} phút</span>
                        </div>
                        @auth
                            @if(auth()->user()->isUser())
                                <a href="{{ route('user.appointments.create', ['service_id' => $service->id]) }}" class="btn btn-primary w-100">
                                    <i class="bi bi-calendar-check"></i> Đặt lịch
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đặt lịch
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có dịch vụ nào.</p>
            </div>
            @endforelse
        </div>
        
        @if($services->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('user.appointments.create') }}" class="btn btn-secondary">
                Xem tất cả dịch vụ <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Products Section -->
@if(isset($products) && $products->count() > 0)
<section style="padding: 80px 0; background-color: var(--bg-light);">
    <div class="container">
        <div class="section-title">
            <h2>Sản phẩm chăm sóc tóc nổi bật</h2>
            <p>Chăm sóc tóc của bạn với những sản phẩm chất lượng cao</p>
        </div>
        
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="bi bi-box-seam" style="font-size: 4rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary fw-bold fs-5">{{ number_format($product->price) }} VNĐ</span>
                            <span class="badge bg-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                @if($product->stock > 10)
                                    Còn hàng
                                @elseif($product->stock > 0)
                                    Sắp hết ({{ $product->stock }})
                                @else
                                    Hết hàng
                                @endif
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.products.show', $product) }}" class="btn btn-primary flex-fill">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                            @auth
                                @if(auth()->user()->isUser() && $product->stock > 0)
                                    <form action="{{ route('user.cart.add') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-outline-primary" title="Thêm vào giỏ hàng">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('user.products.index') }}" class="btn btn-secondary">
                Xem tất cả sản phẩm <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Staff Section -->
@if($staffs->count() > 0)
<section style="padding: 80px 0; background-color: var(--bg-light);">
    <div class="container">
        <div class="section-title">
            <h2>Đội ngũ thợ của chúng tôi</h2>
            <p>Những chuyên gia hàng đầu trong lĩnh vực cắt tóc</p>
        </div>
        
        <div class="row">
            @forelse($staffs as $staff)
            <div class="col-md-4 mb-4">
                <div class="card">
                    @if($staff->avatar)
                        <img src="{{ asset('storage/' . $staff->avatar) }}" class="card-img-top" alt="{{ $staff->name }}" style="height: 300px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="bi bi-person-circle" style="font-size: 5rem; color: #ccc;"></i>
                        </div>
                    @endif
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $staff->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-star-fill text-warning"></i>
                            <strong>Chuyên môn:</strong> {{ $staff->specialization }}
                        </p>
                        @if($staff->bio)
                            <p class="card-text">{{ Str::limit($staff->bio, 100) }}</p>
                        @endif
                        @if($staff->services->count() > 0)
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-check-circle"></i> {{ $staff->services->count() }} dịch vụ
                                </small>
                            </div>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('user.staffs.show', $staff) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Chưa có thợ nào.</p>
            </div>
            @endforelse
        </div>
        
        @if($staffs->count() > 0)
        <div class="text-center mt-4">
            <p class="text-muted">Hiển thị tất cả {{ $staffs->count() }} thợ</p>
        </div>
        @endif
    </div>
</section>
@endif

<!-- Contact & Thank You Section -->
<section style="padding: 80px 0; background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%); color: var(--text-light);">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 style="color: var(--text-light); margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">Thông tin liên hệ</h2>
                <div class="contact-info">
                    <div class="mb-3">
                        <i class="bi bi-geo-alt-fill me-2" style="font-size: 1.2rem;"></i>
                        <strong>Địa chỉ:</strong> 261/23 Thới tây 1, Tân Hiệp, Hóc Môn, Thành phố Hồ Chí Minh
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-telephone-fill me-2" style="font-size: 1.2rem;"></i>
                        <strong>Điện thoại:</strong> <a href="tel:+84987665918" style="color: var(--text-light); text-decoration: none;">0987 665 918</a>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-envelope-fill me-2" style="font-size: 1.2rem;"></i>
                        <strong>Email:</strong> <a href="mailto:info@barbershop.com" style="color: var(--text-light); text-decoration: none;">info@barbershop.com</a>
                    </div>
                    <div class="mb-3">
                        <i class="bi bi-clock-fill me-2" style="font-size: 1.2rem;"></i>
                        <strong>Giờ làm việc:</strong><br>
                        <span style="margin-left: 1.8rem;">Thứ 2 - Chủ nhật: 9:00 - 19:00</span>
                    </div>
                    <div class="mt-4">
                        <h5 style="color: var(--text-light); margin-bottom: 1rem;">Theo dõi chúng tôi</h5>
                        <div class="social-links">
                            <a href="https://www.facebook.com/tiemtocnamduc" class="text-light me-3" style="font-size: 1.5rem;" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="instagram.com/Duc.dami" class="text-light me-3" style="font-size: 1.5rem;" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="tiktok.com/@Duc.dami" class="text-light me-3" style="font-size: 1.5rem;" title="Tiktok">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            <!-- <a href="#" class="text-light" style="font-size: 1.5rem;" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a> -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="text-center">
                    <h2 style="color: var(--text-light); margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">Cảm ơn bạn đã ghé thăm!</h2>
                    <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95; line-height: 1.8;">
                        Chúng tôi rất vui khi được phục vụ bạn. Hãy để chúng tôi giúp bạn có được diện mạo hoàn hảo nhất.
                    </p>
                    <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.9; font-style: italic;">
                        "Come in for the haircut, walk out on top of your game"
                    </p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        @auth
                            @if(auth()->user()->isUser())
                                <a href="{{ route('user.appointments.create') }}" class="btn btn-primary" style="background-color: var(--secondary-color); border-color: var(--secondary-color);">
                                    <i class="bi bi-calendar-check"></i> Đặt lịch ngay
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: var(--secondary-color); border-color: var(--secondary-color);">
                                <i class="bi bi-person-plus"></i> Đăng ký ngay
                            </a>
                        @endauth
                        <a href="{{ route('user.products.index') }}" class="btn btn-secondary" style="border-color: var(--text-light); color: var(--text-light);">
                            <i class="bi bi-bag"></i> Xem sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <hr style="border-color: rgba(255,255,255,0.2); margin: 3rem 0 2rem;">
        
        <div class="text-center">
            <p style="opacity: 0.8; margin-bottom: 0.5rem;">
                <i class="bi bi-heart-fill text-danger"></i> 
                Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi
            </p>
            <p style="opacity: 0.7; font-size: 0.9rem; margin: 0;">
                © {{ date('Y') }} Only 1 Men's Hair Design. All rights reserved.
            </p>
        </div>
    </div>
</section>
@endsection
