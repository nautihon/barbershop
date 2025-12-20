@extends('layouts.app')

@section('title', 'Lịch làm việc')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lịch làm việc - {{ $staff->name }}</h2>
        <a href="{{ route('admin.staffs.show', $staff) }}" class="btn btn-secondary">Quay lại</a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.staffs.store-schedule', $staff) }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>Thứ</th>
                            <th>Làm việc</th>
                            <th>Giờ bắt đầu</th>
                            <th>Giờ kết thúc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                            $existingSchedules = $staff->schedules->keyBy('day_of_week');
                        @endphp
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $schedule = $existingSchedules->get($i);
                            @endphp
                            <tr>
                                <td>{{ $days[$i] }}</td>
                                <td>
                                    <input type="checkbox" name="schedules[{{ $i }}][enabled]" value="1" {{ $schedule ? 'checked' : '' }} onchange="toggleSchedule({{ $i }})">
                                </td>
                                <td>
                                    <input type="time" name="schedules[{{ $i }}][start_time]" class="form-control schedule-time" value="{{ $schedule ? $schedule->start_time : '09:00' }}" {{ $schedule ? '' : 'disabled' }} id="start{{ $i }}">
                                </td>
                                <td>
                                    <input type="time" name="schedules[{{ $i }}][end_time]" class="form-control schedule-time" value="{{ $schedule ? $schedule->end_time : '18:00' }}" {{ $schedule ? '' : 'disabled' }} id="end{{ $i }}">
                                </td>
                                <input type="hidden" name="schedules[{{ $i }}][day_of_week]" value="{{ $i }}">
                            </tr>
                        @endfor
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Lưu lịch làm việc</button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSchedule(day) {
    const checkbox = event.target;
    const startInput = document.getElementById('start' + day);
    const endInput = document.getElementById('end' + day);
    
    if (checkbox.checked) {
        startInput.disabled = false;
        endInput.disabled = false;
    } else {
        startInput.disabled = true;
        endInput.disabled = true;
    }
}
</script>
@endsection

