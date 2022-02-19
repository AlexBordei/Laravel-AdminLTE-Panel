<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id', 'starting', 'ending', 'status', 'google_event_id'];

    protected $dates = ['starting', 'ending'];

    /**
     * Get the subscription associated with the event.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

}
