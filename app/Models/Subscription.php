<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subscription_type_id',
        'starting',
        'ending',
        'payment_id',
        'status'

    ];

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
}
