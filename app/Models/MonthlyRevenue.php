<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'revenue',
        'order_revenue',
        'appointment_revenue',
        'notes',
        'is_closed',
        'closed_at',
    ];

    protected $casts = [
        'revenue' => 'decimal:2',
        'order_revenue' => 'decimal:2',
        'appointment_revenue' => 'decimal:2',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
    ];
}
