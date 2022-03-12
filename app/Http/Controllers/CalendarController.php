<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Teacher;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $pending_events = Event::whereIn('status', ['pending'])->get();
        $scheduled_events = Event::whereIn('status', ['scheduled', 'confirmed'])->get();
        $reservations = Reservation::all();

        foreach ($pending_events as $key => $event) {
            $subscription = Subscription::where('id', $event->subscription->id)->with(['student', 'teacher', 'instrument', 'room'])->first();

            $pending_events[$key]->subscription = $subscription;
        }


        foreach ($scheduled_events as $key => $event) {
            $subscription = Subscription::where('id', $event->subscription->id)->with(['student', 'teacher', 'instrument', 'room'])->first();

            $scheduled_events[$key]->subscription = $subscription;
        }

        foreach ($reservations as $key => $reservation) {
            $student = Student::where('id', $reservation->student_id)->first(['first_name', 'last_name']);
            $teacher = Teacher::where('id', $reservation->teacher_id)->first(['first_name', 'last_name']);
            $reservations[$key]->student = $student;
            $reservations[$key]->teacher = $teacher;
        }
        return $this->buildResponse('calendar.list', [
            'events' => ['pending' => $pending_events, 'scheduled' => $scheduled_events],
            'reservations' => $reservations
        ]);
    }
}

//TODO: to not allow scheduling an event before or after the subscription range
//TODO: each teacher will have his own calendar
//TODO: when scheduling events a check for invinite events will make event go for 2 years, recurrent
