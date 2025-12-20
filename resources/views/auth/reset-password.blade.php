@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="container" style="padding: 80px 0; min-height: calc(100vh - 200px);">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center" style="background-color: var(--primary-color); color: var(--text-light); padding: 2rem;">
                    <h3 style="margin: 0; font-family: 'Playfair Display', serif;">Đặt lại mật khẩu</h3>
                    <p style="margin: 0.5rem 0 0; opacity: 0.9;">Nhập mật khẩu mới của bạn</p>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mật khẩu phải có ít nhất 8 ký tự.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key"></i> Đặt lại mật khẩu
                            </button>
                        </div>
                    </form>
                    
                    <hr class="my-3">
                    <div class="text-center">
                        <a href="{{ route('login') }}" style="color: var(--primary-color); text-decoration: none;">
                            <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

