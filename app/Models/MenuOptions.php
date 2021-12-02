<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOptions extends Model
{
    use HasFactory;

    protected $hidden = array('created_at', 'updated_at');
}
