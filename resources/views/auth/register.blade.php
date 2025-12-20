@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="container" style="padding: 80px 0; min-height: calc(100vh - 200px);">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background-color: var(--primary-color); color: var(--text-light); padding: 2rem;">
                    <h3 style="margin: 0; font-family: 'Playfair Display', serif;">Đăng ký tài khoản</h3>
                    <p style="margin: 0.5rem 0 0; opacity: 0.9;">Tham gia cùng chúng tôi ngay hôm nay</p>
                </div>
                <div class="card-body" style="padding: 2rem;">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
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
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Đăng ký
                        </button>
                    </div>
                    <hr class="my-3">
                    <div class="text-center">
                        <p class="text-muted mb-0">Đã có tài khoản?</p>
                        <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 500; text-decoration: none;">Đăng nhập ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

