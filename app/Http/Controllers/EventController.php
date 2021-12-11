<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::with([
            'teacher',
            'student',
            'instrument',
            'room'
        ])->get();
        return $this->buildResponse('event.list', $events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::all(['id', 'first_name', 'last_name']);
        $teachers = Teacher::all(['id', 'first_name', 'last_name']);
        $instruments = Instrument::all(['id', 'name']);
        $rooms = Room::all(['id', 'name']);
        $statuses = [
            'new',
            'confirmed',
            'canceled'
        ];

        return $this->buildResponse('event.create', [
            'students' => $students,
            'teachers' => $teachers,
            'instruments' => $instruments,
            'rooms' => $rooms,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'required|exists:rooms,id',
            'instrument_id' => 'required|exists:instruments,id',
            'time_interval' => 'required|max:255',
            'status' => 'required|in:new,confirmed,canceled',
        ]);

        $starting = explode(' - ', $request->get('time_interval'))[0];
        $ending = explode(' - ', $request->get('time_interval'))[1];

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $starting);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $ending);

        Event::create(
            [
                'student_id' => $request->get('student_id'),
                'teacher_id' => $request->get('teacher_id'),
                'room_id' => $request->get('room_id'),
                'instrument_id' => $request->get('instrument_id'),
                'starting' => $starting_date,
                'ending' => $ending_date,
                'status' => $request->get('status'),
            ]
        );

        return redirect('/event')->with('success', 'Event has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $students = Student::all(['id', 'first_name', 'last_name']);
        $teachers = Teacher::all(['id', 'first_name', 'last_name']);
        $instruments = Instrument::all(['id', 'name']);
        $rooms = Room::all(['id', 'name']);
        $statuses = [
            'new',
            'confirmed',
            'canceled'
        ];
        $event = Event::with([
            'teacher',
            'student',
            'instrument',
            'room'
        ])->where('id', $event->id)->first();

        return $this->buildResponse('event.edit', [
            'students' => $students,
            'teachers' => $teachers,
            'instruments' => $instruments,
            'rooms' => $rooms,
            'statuses' => $statuses,
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEventRequest $request, Event $event)
    {

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'required|exists:rooms,id',
            'instrument_id' => 'required|exists:instruments,id',
            'time_interval' => 'required|max:255',
            'status' => 'required|in:new,confirmed,canceled',
        ]);

        $starting = explode(' - ', $request->get('time_interval'))[0];
        $ending = explode(' - ', $request->get('time_interval'))[1];

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $starting);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $ending);

        $event->fill([
            'student_id' => $request->get('student_id'),
            'teacher_id' => $request->get('teacher_id'),
            'room_id' => $request->get('room_id'),
            'instrument_id' => $request->get('instrument_id'),
            'starting' => $starting_date,
            'ending' => $ending_date,
            'status' => $request->get('status'),
        ])->save();

        return redirect()->route('event.index')->with('success', 'Event successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Event $event)
    {

        try {
            $event->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error deleting the event!']);
        }
    }
}
