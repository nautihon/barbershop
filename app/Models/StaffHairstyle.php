<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffHairstyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'title',
        'description',
        'image',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
