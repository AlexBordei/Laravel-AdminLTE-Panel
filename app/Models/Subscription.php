<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'room_id',
        'instrument_id',
        'subscription_type_id',
        'starting',
        'ending',
        'payment_id',
        'status',
        'comment'
    ];

    protected $dates = ['starting', 'ending'];
    /**
     * Get the student associated with the subscription.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subcription type associated with the subscription.
     */
    public function subscription_type()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    /**
     * Get the teacher associated with the event.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the instrument associated with the event.
     */
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
    /**
     * Get the room associated with the event.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
