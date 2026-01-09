@extends('layouts.app')

@section('title', 'Đặt lịch hẹn')

@section('content')
<div class="container mt-4">
    <h2>Đặt lịch hẹn</h2>
    
    @if(isset($staffNotWorkingToday) && $staffNotWorkingToday)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <strong>Lưu ý:</strong> Hôm này thợ không có lịch làm việc.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
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
                    <select class="form-select @error('staff_id') is-invalid @enderror" id="staff_id" name="staff_id" required onchange="checkStaffAvailability(); updateBookedTimeSlots();">
                        <option value="">Chọn thợ</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" 
                                    data-staff-id="{{ $staff->id }}"
                                    {{ old('staff_id', $selectedStaffId ?? request('staff_id')) == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }} - {{ $staff->specialization }}
                            </option>
                        @endforeach
                    </select>
                    <div id="staff-availability-message" class="mt-2" style="display: none;"></div>
                    <small class="form-text text-muted">Vui lòng chọn ngày hẹn để kiểm tra lịch làm việc của thợ.</small>
                    @error('staff_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Ngày hẹn</label>
                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date', $selectedDate ?? (isset($selectedStaffId) ? date('Y-m-d') : '')) }}" min="{{ date('Y-m-d') }}" required onchange="updateAvailableStaffs(); checkStaffAvailability(); updateBookedTimeSlots();">
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
                                <option value="{{ $timeValue }}" data-time="{{ $timeValue }}" {{ $selectedTime == $timeValue ? 'selected' : '' }}>
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

<script>
let staffAvailabilityData = {};

function updateAvailableStaffs() {
    const date = document.getElementById('appointment_date').value;
    const serviceId = document.getElementById('service_id').value;
    
    if (!date) {
        staffAvailabilityData = {};
        checkStaffAvailability();
        return;
    }
    
    // Lấy staff_id đã chọn (nếu có)
    const selectedStaffId = document.getElementById('staff_id').value;
    
    // Fetch staff availability info
    let url = `{{ route('user.appointments.available-staffs') }}?date=${date}&service_id=${serviceId || ''}`;
    if (selectedStaffId) {
        url += `&staff_id=${selectedStaffId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Lưu thông tin availability vào object
            staffAvailabilityData = {};
            data.staffs.forEach(staff => {
                staffAvailabilityData[staff.id] = staff.is_working;
            });
            
            // Kiểm tra lại staff đã chọn
            checkStaffAvailability();
        })
        .catch(error => {
            console.error('Error:', error);
            staffAvailabilityData = {};
            checkStaffAvailability();
        });
}

function checkStaffAvailability() {
    const date = document.getElementById('appointment_date').value;
    const staffId = document.getElementById('staff_id').value;
    const messageDiv = document.getElementById('staff-availability-message');
    
    // Ẩn thông báo nếu chưa chọn đủ thông tin
    if (!date || !staffId) {
        messageDiv.style.display = 'none';
        messageDiv.innerHTML = '';
        return;
    }
    
    // Kiểm tra thông tin availability
    if (staffAvailabilityData.hasOwnProperty(staffId)) {
        const isWorking = staffAvailabilityData[staffId];
        
        if (!isWorking) {
            // Format ngày để hiển thị đẹp
            const dateObj = new Date(date);
            const day = String(dateObj.getDate()).padStart(2, '0');
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const year = dateObj.getFullYear();
            const formattedDate = `${day}/${month}/${year}`;
            
            // Kiểm tra xem có phải hôm nay không
            const today = new Date().toISOString().split('T')[0];
            const dateText = date === today ? 'Hôm nay' : `Ngày ${formattedDate}`;
            
            messageDiv.style.display = 'block';
            messageDiv.className = 'alert alert-warning mt-2';
            messageDiv.innerHTML = `<i class="bi bi-exclamation-triangle"></i> <strong>Lưu ý:</strong> ${dateText} thợ không có lịch làm việc.`;
        } else {
            messageDiv.style.display = 'none';
            messageDiv.innerHTML = '';
        }
    } else {
        // Nếu chưa có dữ liệu, ẩn thông báo
        messageDiv.style.display = 'none';
        messageDiv.innerHTML = '';
    }
}

let bookedTimeSlots = [];

function updateBookedTimeSlots() {
    const date = document.getElementById('appointment_date').value;
    const staffId = document.getElementById('staff_id').value;
    
    if (!date || !staffId) {
        bookedTimeSlots = [];
        updateTimeSlots();
        return;
    }
    
    // Fetch booked time slots
    fetch(`{{ route('user.appointments.booked-times') }}?date=${date}&staff_id=${staffId}`)
        .then(response => response.json())
        .then(data => {
            bookedTimeSlots = data.booked_times || [];
            console.log('Booked time slots:', bookedTimeSlots); // Debug
            updateTimeSlots();
        })
        .catch(error => {
            console.error('Error fetching booked times:', error);
            bookedTimeSlots = [];
            updateTimeSlots();
        });
}

function updateTimeSlots() {
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');
    const selectedDate = dateInput.value;
    const today = new Date().toISOString().split('T')[0];
    
    if (!selectedDate) {
        return;
    }
    
    // Lấy giá trị đã chọn trước đó (nếu có)
    const previouslySelected = timeSelect.value;
    
    // Duyệt qua tất cả options có data-time
    const options = timeSelect.querySelectorAll('option[data-time]');
    let hasValidOption = false;
    
    options.forEach(option => {
        const timeValue = option.getAttribute('data-time');
        if (!timeValue) return;
        
        const [hour, minute] = timeValue.split(':').map(Number);
        const timeInMinutes = hour * 60 + minute;
        
        // Kiểm tra nếu là ngày hôm nay, ẩn các khung giờ đã qua
        let isPastTime = false;
        if (selectedDate === today) {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const currentTime = currentHour * 60 + currentMinute;
            isPastTime = timeInMinutes <= currentTime;
        }
        
        // Kiểm tra nếu khung giờ đã được đặt
        const isBooked = bookedTimeSlots.includes(timeValue);
        
        if (isPastTime || isBooked) {
            // Ẩn khung giờ đã qua hoặc đã được đặt
            option.style.display = 'none';
            option.disabled = true;
            option.hidden = true;
            option.removeAttribute('selected');
            // Nếu option này đang được chọn, bỏ chọn
            if (option.selected) {
                option.selected = false;
                timeSelect.value = '';
            }
        } else {
            // Hiển thị khung giờ còn trống
            option.style.display = '';
            option.disabled = false;
            option.removeAttribute('hidden');
            hasValidOption = true;
        }
    });
    
    // Cập nhật text cho option đầu tiên
    const firstOption = timeSelect.querySelector('option[value=""]');
    if (firstOption) {
        if (!hasValidOption) {
            if (selectedDate === today) {
                firstOption.textContent = 'Không còn khung giờ nào trong ngày hôm nay';
            } else {
                firstOption.textContent = 'Tất cả khung giờ đã được đặt';
            }
        } else {
            firstOption.textContent = 'Chọn giờ';
        }
    }
    
    // Khôi phục giá trị đã chọn trước đó (nếu có và vẫn hợp lệ)
    if (previouslySelected && !bookedTimeSlots.includes(previouslySelected)) {
        const selectedOption = timeSelect.querySelector(`option[value="${previouslySelected}"]`);
        if (selectedOption && !selectedOption.disabled) {
            timeSelect.value = previouslySelected;
        }
    }
}

// Update staffs when service changes (if date is already selected)
document.getElementById('service_id').addEventListener('change', function() {
    const date = document.getElementById('appointment_date').value;
    if (date) {
        updateAvailableStaffs();
    }
    checkStaffAvailability();
});

// Khởi tạo khi trang load (nếu đã chọn ngày hiện tại)
document.addEventListener('DOMContentLoaded', function() {
    const staffId = document.getElementById('staff_id').value;
    const appointmentDate = document.getElementById('appointment_date').value;
    
    // Cập nhật time slots và booked times
    if (staffId && appointmentDate) {
        updateBookedTimeSlots();
    } else {
        updateTimeSlots();
    }
    
    // Nếu có staff_id được chọn từ trang show, tự động kiểm tra lịch làm việc
    if (staffId && appointmentDate) {
        // Nếu ngày là hôm nay, kiểm tra ngay
        const today = new Date().toISOString().split('T')[0];
        if (appointmentDate === today) {
            updateAvailableStaffs();
        }
    }
});
</script>
@endsection

