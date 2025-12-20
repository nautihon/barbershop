@extends('layouts.app')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="container" style="padding: 80px 0; min-height: calc(100vh - 200px);">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center" style="background-color: var(--primary-color); color: var(--text-light); padding: 2rem;">
                    <h3 style="margin: 0; font-family: 'Playfair Display', serif;">Quên mật khẩu</h3>
                    <p style="margin: 0.5rem 0 0; opacity: 0.9;">Nhập email để đặt lại mật khẩu</p>
                </div>
                <div class="card-body" style="padding: 2rem;">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            @if(session('reset_link'))
                                <hr class="my-2">
                                <p class="mb-1"><small><strong>Link đặt lại mật khẩu (cho development):</strong></small></p>
                                <a href="{{ session('reset_link') }}" class="text-white text-decoration-underline" target="_blank">
                                    <small>{{ session('reset_link') }}</small>
                                </a>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Chúng tôi sẽ gửi liên kết đặt lại mật khẩu đến email này.</small>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-envelope"></i> Gửi liên kết đặt lại mật khẩu
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

