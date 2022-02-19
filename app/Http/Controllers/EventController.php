<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Instrument;
use App\Models\Room;
use App\Models\Student;
use App\Models\Subscription;
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
            'subscription',
        ])->get();

        foreach ($events as $key => $event) {
            $student = Student::where('id', $event->subscription->student_id)->first(['first_name', 'last_name']);
            $events[$key]->student = $student;
        }
        return $this->buildResponse('event.list', $events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subscriptions = Subscription::with([
            'student',
        ])->get();

        $statuses = [
            'pending',
            'scheduled',
            'confirmed',
            'canceled'
        ];

        return $this->buildResponse('event.create', [
            'subscriptions' => $subscriptions,
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
            'subscription_id' => 'required|exists:subscriptions,id',
            'time_interval' => 'required|max:255',
            'status' => 'required|in:pending,scheduled,confirmed,canceled',
        ]);

        $starting = explode(' - ', $request->get('time_interval'))[0];
        $ending = explode(' - ', $request->get('time_interval'))[1];

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $starting);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $ending);

        $g_event_response = $this->create_google_event($request);
        Event::create(
            [
                'subscription_id' => $request->get('subscription_id'),
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
        $subscriptions = Subscription::with('student')->get();

        $statuses = [
            'pending',
            'scheduled',
            'confirmed',
            'canceled'
        ];
        $event = Event::with([
            'subscription',
        ])->where('id', $event->id)->first();

        return $this->buildResponse('event.edit', [
            'subscriptions' => $subscriptions,
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
            'subscription_id' => 'required|exists:subscriptions,id',
            'time_interval' => 'required|max:255',
            'status' => 'required|in:pending,scheduled,confirmed,canceled',
        ]);

        $starting = explode(' - ', $request->get('time_interval'))[0];
        $ending = explode(' - ', $request->get('time_interval'))[1];

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $starting);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $ending);

        $g_event_response = $this->update_google_event($request, $event);

        $event->fill([
            'subscription_id' => $request->get('subscription_id'),
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
            if(!empty($event->google_event_id) ) {
                $this->delete_google_event($event);
            }

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

        $subscription = Subscription::where('id', $request->get('subscription_id'))->with(
            [
                'student',
                'teacher',
                'instrument',
                'room',
        ]
        )->first();

        $g_event = [];

        $g_event['name'] = $subscription->instrument->name . ' ' . $subscription->student->first_name . ' ' . $subscription->student->last_name;
        $g_event['description']  = "Student: " . ' ' . $subscription->student->first_name . ' ' . $subscription->student->last_name . "\n";
        $g_event['description']  .= "Room: " . ' ' . $subscription->room->name . "\n";
        $g_event['description']  .= "Instrument: " . ' ' . $subscription->instrument->name . "\n";
        $g_event['description']  .= "Status: " . ' ' . $request->get('status') . "\n";
        $g_event['startDateTime']  = Carbon::create($starting_date);
        $g_event['endDateTime']  = Carbon::create($ending_date);

        $g_event_response = null;

        try {
            if (!empty($subscription->teacher->google_calendar_id)) {
                try {
                    $g_event_response = \Spatie\GoogleCalendar\Event::create($g_event, $subscription->teacher->google_calendar_id);
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
                $subscription = Subscription::where('id', $event->subscription_id)->with('teacher')->first();

                $g_event = null;
                if (!empty($subscription->teacher->google_calendar_id)) {
                    try {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id, $subscription->teacher->google_calendar_id);
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
                $subscription = Subscription::where('id', $event->subscription_id)->with('teacher')->first();
                $g_event = null;
                if (!empty($subscription->teacher->google_calendar_id)) {
                    try {
                        $g_event = \Spatie\GoogleCalendar\Event::find($event->google_event_id, $subscription->teacher->google_calendar_id);
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
