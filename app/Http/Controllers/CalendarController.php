<?php

namespace App\Http\Controllers;

use App\Events\DeleteReservationEvent;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Teacher;

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

            $scheduled_events[$key]->subscription = $subscription;
        }

        foreach ($reservations as $key => $reservation) {
            $student = Student::where('id', $reservation->student_id)->first(['first_name', 'last_name']);
            $teacher = Teacher::where('id', $reservation->teacher_id)->first(['first_name', 'last_name']);
            $reservations[$key]->student = $student;
            $reservations[$key]->teacher = $teacher;

            event(new DeleteReservationEvent($reservation[$key])); // nu stiu daca aici iti trb neapat, dar e un exemplu de apelare
            // iar tu daca o sa ai nevoie oriunde acum, poti doar apela eventul asta
            // poti sa ii pui si queues ca de ex daca tu ai 15 rezervari de sters una dupa alta sa nu se incetineasca aplicatia
            // e doar un exemlu insa poti sa folosesti eventurile oriunde, sunt super folositoare mai ales pt performanta
        }

        array_filter($reservations->toArray(), function($e) use (&$grouped_reservations){
            $grouped_reservations['student_id_' . $e['student_id']] = array(
                'student_id' => $e['student_id'],
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
