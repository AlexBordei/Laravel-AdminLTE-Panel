<?php

namespace App\Http\Controllers;

use App\Events\DeleteGoogleCalendarEvent;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Teacher;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        $students = Student::all();
        $rooms = Room::all();
        $instruments = Instrument::all();

        $pending_events = Event::whereIn('status', ['pending'])->get();
        $scheduled_events = Event::whereIn('status', ['scheduled', 'confirmed'])->get();
        $reservations = Reservation::all();
        $grouped_reservations = [];

        foreach ($pending_events as $key => $event) {
            $subscription = Subscription::where('id', $event->subscription->id)->with(['student', 'teacher', 'instrument', 'room'])->first();

            $pending_events[$key]->subscription = $subscription;
        }

        foreach ($scheduled_events as $key => $event) {
            $subscription = Subscription::where('id', $event->subscription->id)->with(['student', 'teacher', 'instrument', 'room'])->first();
            if($event->ending->lt(Carbon::now())) {
                $scheduled_events[$key]->expired = true;
            }
            $scheduled_events[$key]->subscription = $subscription;
        }

        foreach ($reservations as $key => $reservation) {
            $student = Student::where('id', $reservation->student_id)->first(['first_name', 'last_name']);
            $teacher = Teacher::where('id', $reservation->teacher_id)->first(['first_name', 'last_name']);
            $reservations[$key]->student = $student;
            $reservations[$key]->teacher = $teacher;

            if((new Carbon($reservation['ending']))->lt(Carbon::now())) {
                event(new DeleteGoogleCalendarEvent($reservations[$key]));
                $reservations[$key]->delete();
                unset($reservations[$key]);
            }
        }

        array_filter($reservations->toArray(), function($e) use (&$grouped_reservations){
            $grouped_reservations['student_id_' . $e['student_id']] = array(
                'student_id' => $e['student_id'],
                'subscription_id' => $e['subscription_id'],
                'first_name' => $e['student']['first_name'],
                'last_name' => $e['student']['last_name'],
                'reservations' => [],
            );
        });

        array_filter($reservations->toArray(), function($e) use (&$grouped_reservations){
            unset($e['student']);

            $grouped_reservations['student_id_' . $e['student_id']]['reservations'][] = $e;
        });



        return $this->buildResponse('calendar.list', [
            'events' => [
                'pending' => $pending_events,
                'scheduled' => $scheduled_events
            ],
            'reservations' => $reservations,
            'teachers' => $teachers,
            'students' => $students,
            'rooms' => $rooms,
            'instruments' => $instruments,
            'grouped_reservations' => $grouped_reservations
        ]);
    }
}

//TODO: to not allow scheduling an event before or after the subscription range
//TODO: when scheduling events a check for invinite events will make event go for 2 years, recurrent
