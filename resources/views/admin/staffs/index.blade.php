@extends('layouts.app')

@section('title', 'Quản lý nhân viên')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Quản lý nhân viên</h2>
        <a href="{{ route('admin.staffs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chuyên môn</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $staff)
                    <tr>
                        <td>{{ $staff->id }}</td>
                        <td>
                            @if($staff->avatar)
                                <img src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            @else
                                <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                            @endif
                        </td>
                        <td>{{ $staff->name }}</td>
                        <td>{{ $staff->email }}</td>
                        <td>{{ $staff->phone }}</td>
                        <td>{{ Str::limit($staff->specialization, 30) }}</td>
                        <td>
                            @php
                                $dynamicStatus = $staff->dynamic_status;
                            @endphp
                            <span class="badge bg-{{ $dynamicStatus == 'active' ? 'success' : 'secondary' }}" title="{{ $dynamicStatus == 'active' ? 'Đang hoạt động hôm nay' : 'Tạm ngừng hôm nay (không có lịch làm việc hoặc đã xin nghỉ)' }}">
                                {{ $dynamicStatus == 'active' ? 'Hoạt động' : 'Tạm ngừng' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.staffs.show', $staff) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.staffs.edit', $staff) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin.staffs.schedule', $staff) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-calendar"></i>
                            </a>
                            <form action="{{ route('admin.staffs.destroy', $staff) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $staffs->links() }}
        </div>
    </div>
</div>
@endsection

