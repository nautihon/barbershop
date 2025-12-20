@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container" style="padding: 80px 0; min-height: calc(100vh - 200px);">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center" style="background-color: var(--primary-color); color: var(--text-light); padding: 2rem;">
                    <h3 style="margin: 0; font-family: 'Playfair Display', serif;">Đăng nhập</h3>
                    <p style="margin: 0.5rem 0 0; opacity: 0.9;">Chào mừng trở lại</p>
                </div>
                <div class="card-body" style="padding: 2rem;">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </button>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" style="color: var(--primary-color); text-decoration: none;">Quên mật khẩu?</a>
                    </div>
                    <hr class="my-3">
                    <div class="text-center">
                        <p class="text-muted mb-0">Chưa có tài khoản?</p>
                        <a href="{{ route('register') }}" style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

