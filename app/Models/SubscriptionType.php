<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sessions_number',
        'sessions_per_week',
        'price',
        'duration',
        'is_band',
        'instruments_number',
        'students_number',

    ];
}
