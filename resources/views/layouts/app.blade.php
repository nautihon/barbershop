<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barbershop')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #d4af37;
            --accent-color: #2c2c2c;
            --text-light: #f5f5f5;
            --text-dark: #333;
            --bg-light: #fafafa;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background-color: #fff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }
        
        .navbar {
            background-color: var(--primary-color) !important;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.75rem;
            color: var(--text-light) !important;
            letter-spacing: 0.5px;
        }
        
        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            font-weight: 400;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--secondary-color) !important;
        }
        
        .navbar-dark .navbar-toggler {
            border-color: var(--text-light);
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            border: none;
            border-radius: 8px;
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-light);
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: var(--text-light);
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(44, 44, 44, 0.85) 100%),
                        url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80') center center / cover no-repeat;
            color: var(--text-light);
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 500px;
            display: flex;
            align-items: center;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
            z-index: 1;
        }
        
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
        }
        
        .hero-cta {
            position: relative;
            z-index: 2;
        }
        
        .hero-cta .btn {
            font-size: 1.1rem;
            padding: 1rem 3rem;
            margin: 0.5rem;
        }
        
        .feature-section {
            padding: 80px 0;
            background-color: var(--bg-light);
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            height: 100%;
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #666;
        }
        
        .main-content {
            min-height: calc(100vh - 56px);
        }
        
        .dropdown-menu {
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
        }
        
        .dropdown-item:hover {
            background-color: var(--bg-light);
        }
        
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-scissors"></i> Only 1 Men's Hair Design
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.services.index') }}">Dịch vụ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.staffs.index') }}">Nhân viên</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.appointments.index') }}">Lịch hẹn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.products.index') }}">Sản phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.orders.index') }}">Đơn hàng</a>
                            </li>
                        @elseif(auth()->user()->isStaff())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.appointments.index') }}">Lịch làm việc</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.hairstyles.index') }}">Kiểu tóc nổi bật</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('staff.leave-requests.index') }}">Đơn xin nghỉ</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.home') }}">Trang chủ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Xem dịch vụ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.appointments.index') }}">Lịch hẹn</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.products.index') }}">Sản phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.cart.index') }}">
                                    <i class="bi bi-cart"></i> Giỏ hàng
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.orders.index') }}">Đơn hàng</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.profile.index') }}">Thông tin</a>
                            </li> -->
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">Thông tin</a></li>
                                @elseif(auth()->user()->isStaff())
                                    <li><a class="dropdown-item" href="{{ route('staff.profile.index') }}">Thông tin</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('user.profile.index') }}">Thông tin</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3 mx-3" role="alert" style="max-width: 1200px; margin-left: auto; margin-right: auto;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" role="alert" style="max-width: 1200px; margin-left: auto; margin-right: auto;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3 mx-3" role="alert" style="max-width: 1200px; margin-left: auto; margin-right: auto;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

