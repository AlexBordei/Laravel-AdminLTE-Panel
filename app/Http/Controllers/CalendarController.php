<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Subscription;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'pending')->get();

        foreach ($events as $key => $event) {
            $subscription = Subscription::where('id', $event->subscription->id)->with(['student', 'teacher', 'instrument', 'room'])->first();

            $events[$key]->subscription = $subscription;
        }
        return $this->buildResponse('calendar.list', ['events' => $events]);
    }
}

//TODO: to not allow scheduling an event before or after the subscription range
//TODO: each teacher will have his own calendar
//TODO: when scheduling events a check for invinite events will make event go for 2 years, recurrent
