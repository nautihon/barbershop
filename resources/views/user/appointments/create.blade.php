@extends('layouts.app')

@section('title', 'Đặt lịch hẹn')

@section('content')
<div class="container mt-4">
    <h2>Đặt lịch hẹn</h2>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('user.appointments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="service_id" class="form-label">Dịch vụ</label>
                    <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                        <option value="">Chọn dịch vụ</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $selectedServiceId ?? request('service_id')) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - {{ number_format($service->price) }} VNĐ ({{ $service->duration }} phút)
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="staff_id" class="form-label">Thợ cắt tóc</label>
                    <select class="form-select @error('staff_id') is-invalid @enderror" id="staff_id" name="staff_id" required>
                        <option value="">Chọn thợ</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ old('staff_id', $selectedStaffId ?? request('staff_id')) == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }} - {{ $staff->specialization }}
                            </option>
                        @endforeach
                    </select>
                    @error('staff_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Ngày hẹn</label>
                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    @error('appointment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="appointment_time" class="form-label">Giờ hẹn</label>
                    <select class="form-select @error('appointment_time') is-invalid @enderror" id="appointment_time" name="appointment_time" required>
                        <option value="">Chọn giờ</option>
                        @php
                            $startHour = 9;
                            $endHour = 19;
                            $selectedTime = old('appointment_time');
                        @endphp
                        @for($hour = $startHour; $hour <= $endHour; $hour++)
                            @foreach(['00', '30'] as $minute)
                                @php
                                    $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                    if ($hour == $endHour && $minute == '30') break;
                                @endphp
                                <option value="{{ $timeValue }}" {{ $selectedTime == $timeValue ? 'selected' : '' }}>
                                    {{ $timeValue }}
                                </option>
                            @endforeach
                        @endfor
                    </select>
                    @error('appointment_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Ghi chú</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Đặt lịch</button>
                <a href="{{ route('user.appointments.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection

