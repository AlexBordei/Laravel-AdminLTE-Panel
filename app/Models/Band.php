<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'students'];

    /**
     * The students that belong to the bands.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
