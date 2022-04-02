<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'phone', 'email', 'birth_date', 'google_calendar_id', 'calendar_color', 'instrument_ids', 'room_id'];

}
