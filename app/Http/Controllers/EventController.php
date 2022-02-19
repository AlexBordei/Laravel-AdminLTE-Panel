<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Google\Service\Exception;

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

        $g_event_response = $this->create_google_event($request);
        Event::create(
            [
                'student_id' => $request->get('student_id'),
                'teacher_id' => $request->get('teacher_id'),
                'room_id' => $request->get('room_id'),
                'instrument_id' => $request->get('instrument_id'),
                'starting' => $starting_date,
                'ending' => $ending_date,
                'status' => $request->get('status'),
                'google_event_id' => $g_event_response->id
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

        $g_event_response = $this->update_google_event($request, $event);

        $event->fill([
            'student_id' => $request->get('student_id'),
            'teacher_id' => $request->get('teacher_id'),
            'room_id' => $request->get('room_id'),
            'instrument_id' => $request->get('instrument_id'),
            'starting' => $starting_date,
            'ending' => $ending_date,
            'status' => $request->get('status'),
            'google_event_id' => $g_event_response->id
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
            $this->delete_google_event($event);

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

    private function create_google_event($request) {

        $starting = explode(' - ', $request->get('time_interval'))[0];
        $ending = explode(' - ', $request->get('time_interval'))[1];

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $starting);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $ending);


        $instrument = Instrument::where('id', $request->get('instrument_id'))->first();
        $student = Student::where('id', $request->get('student_id'))->first();
        $room = Room::where('id', $request->get('room_id'))->first();
        $teacher = Teacher::where('id', $request->get('teacher_id'))->first();

        $g_event = [];

        $g_event['name'] = $instrument->name . ' ' . $student->first_name . ' ' . $student->last_name;
        $g_event['description']  = "Student: " . ' ' . $student->first_name . ' ' . $student->last_name . "\n";
        $g_event['description']  .= "Room: " . ' ' . $room->name . "\n";
        $g_event['description']  .= "Instrument: " . ' ' . $instrument->name . "\n";
        $g_event['description']  .= "Status: " . ' ' . $request->get('status') . "\n";
        $g_event['startDateTime']  = Carbon::create($starting_date);
        $g_event['endDateTime']  = Carbon::create($ending_date);

        $g_event_response = null;

        try {
            if (!empty($teacher->google_calendar_id)) {
                try {
                    $g_event_response = \Spatie\GoogleCalendar\Event::create($g_event, $teacher->google_calendar_id);
                } catch (Exception $e) {
                    $g_event_response = \Spatie\GoogleCalendar\Event::create($g_event);
                }
            } else {
                $g_event_response = \Spatie\GoogleCalendar\Event::create($g_event);
            }
        }
        catch (Exception $e) {
            // TODO: add this to error notifications
            return false;
        }

        return $g_event_response;
    }
    private function delete_google_event($event) {
        if(!empty($event->get('google_event_id'))) {
            try {
                $teacher = Teacher::where('id', $event->teacher_id)->first();

                $g_event = null;
                if (!empty($teacher->google_calendar_id)) {
                    try {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id, $teacher->google_calendar_id);
                    } catch (Exception $e) {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id);
                    }
                } else {
                    $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id);
                }
                $g_event->delete();
                return true;
            } catch (Exception $e) {
                // TODO: add this to error notifications
            }
        }
        return false;
    }
    private function update_google_event($request, $event) {
        if(!empty($event->get('google_event_id'))) {
            try {
                $teacher = Teacher::where('id', $event->teacher_id)->first();

                $g_event = null;
                if (!empty($teacher->google_calendar_id)) {
                    try {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id, $teacher->google_calendar_id);
                    } catch (Exception $e) {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id);
                    }
                } else {
                    $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id);
                }
                $g_event->delete();
                return $this->create_google_event($request);
            } catch (Exception $e) {
                // TODO: add this to error notifications
            }
        }
        return false;
    }
}
