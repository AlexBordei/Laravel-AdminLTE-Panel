<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'teacher_id', 'instrument_id', 'room_id', 'starting', 'ending', 'status'];

    protected $dates = ['starting', 'ending'];


    /**
     * Get the teacher associated with the event.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    /**
     * Get the teacher associated with the event.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    /**
     * Get the teacher associated with the event.
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
    /**
     * Get the teacher associated with the event.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

}
