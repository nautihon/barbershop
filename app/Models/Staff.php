<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'specialization',
        'bio',
        'avatar',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_service');
    }

    public function schedules()
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hairstyles()
    {
        return $this->hasMany(StaffHairstyle::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(StaffLeaveRequest::class);
    }

    /**
     * Kiểm tra staff có làm việc vào ngày cụ thể không (dựa trên lịch làm việc)
     */
    public function isWorkingOnDate($date)
    {
        $dayOfWeek = date('w', strtotime($date)); // 0 = Chủ nhật, 1 = Thứ 2, ..., 6 = Thứ 7
        
        return $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->exists();
    }

    /**
     * Kiểm tra staff có xin nghỉ vào ngày cụ thể không (đã được duyệt)
     */
    public function isOnLeaveOnDate($date)
    {
        return $this->leaveRequests()
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }

    /**
     * Kiểm tra staff có sẵn sàng làm việc vào ngày cụ thể không
     * (có lịch làm việc và không xin nghỉ)
     */
    public function isAvailableOnDate($date)
    {
        // Kiểm tra status cơ bản
        if ($this->status !== 'active') {
            return false;
        }

        // Kiểm tra có lịch làm việc vào ngày đó không
        if (!$this->isWorkingOnDate($date)) {
            return false;
        }

        // Kiểm tra có xin nghỉ không
        if ($this->isOnLeaveOnDate($date)) {
            return false;
        }

        return true;
    }

    /**
     * Lấy status động dựa trên ngày hiện tại
     */
    public function getDynamicStatusAttribute()
    {
        $today = date('Y-m-d');
        
        if ($this->status !== 'active') {
            return 'inactive';
        }

        if (!$this->isWorkingOnDate($today)) {
            return 'inactive';
        }

        if ($this->isOnLeaveOnDate($today)) {
            return 'inactive';
        }

        return 'active';
    }
}
