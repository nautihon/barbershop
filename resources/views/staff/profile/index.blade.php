@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid mt-4">
    <h2>Thông tin cá nhân</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="200">Tên:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại:</th>
                            <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Địa chỉ:</th>
                            <td>{{ $user->address ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Vai trò:</th>
                            <td><span class="badge bg-success">Nhân viên</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        @if($staff)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin nhân viên</h5>
                </div>
                <div class="card-body">
                    @if($staff->avatar)
                        <img src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->name }}" class="img-fluid rounded mb-3" style="max-width: 200px;">
                    @endif
                    <table class="table">
                        <tr>
                            <th>Chuyên môn:</th>
                            <td>{{ $staff->specialization ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td>
                                <span class="badge bg-{{ $staff->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $staff->status == 'active' ? 'Đang làm việc' : 'Tạm nghỉ' }}
                                </span>
                            </td>
                        </tr>
                        @if($staff->bio)
                        <tr>
                            <th>Giới thiệu:</th>
                            <td>{{ $staff->bio }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

