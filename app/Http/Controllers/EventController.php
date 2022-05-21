<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionType;
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

        $reservations = Reservation::all();
        foreach ($events as $key => $event) {
            $student = Student::where('id', $event->subscription->student_id)->first(['first_name', 'last_name']);
            $subscription_type = SubscriptionType::where('id', $event->subscription->subscription_type_id)->first();
            $events[$key]->student = $student;
            $events[$key]->subscription_type = $subscription_type;
        }

        foreach ($reservations as $key => $reservation) {
            $student = Student::where('id', $reservation->student_id)->first(['first_name', 'last_name']);
            $teacher = Teacher::where('id', $reservation->teacher_id)->first(['first_name', 'last_name']);
            $reservations[$key]->student = $student;
            $reservations[$key]->teacher = $teacher;
        }
        return $this->buildResponse('event.list', [
            'events' => $events,
            'reservations' => $reservations
        ]);
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
            'event' => $event,
            'redirect_to_calendar' => isset($_GET['redirect_calendar']) && $_GET['redirect_calendar'] === 'yes'
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
            'status' => 'required|in:pending,scheduled,confirmed,canceled',
        ]);

        $old_status = $event->status;
        $event->status = $request->get('status');

        $delete_event_status = false;
        $update_event_status = false;

        if(in_array($event->status, ['pending', 'canceled'])) {

            if($old_status === 'scheduled' && $event->status === 'pending') {
                $event->rescheduled = true;
            }

            $g_event_response = $this->delete_google_event($event);
            $delete_event_status = $g_event_response;
        } else {
            $g_event_response = $this->update_google_event($event);
            $update_event_status = $g_event_response;
        }

        if($delete_event_status === false && $update_event_status === true)  {
            $event->google_event_id = $g_event_response->id;
        } else if($delete_event_status === true && $update_event_status === false) {
            $event->google_event_id = null;
        }

        $event->fill([
            'subscription_id' => $request->get('subscription_id')
        ])->save();

        $route = 'event.index';
        if($request->has('redirect_to_calendar')) {
            $route = 'calendar.index';
        }
        return redirect()->route($route)->with('success', 'Event successfully updated!');
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

            $event->status = 'canceled';
            $event->save();
            return redirect()
                ->back()
                ->with('success', 'Event canceled successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error canceling the event!']);
        }
    }

    private function create_google_event($event, $is_reservation = false) {
        $g_event = [];
        $teacher = null;
        if($is_reservation === true) {
            $student = Student::where('id', $event->student_id)->firstOrFail();
            $teacher = Teacher::where('id', $event->teacher_id)->firstOrFail();

            $g_event['name'] = 'Reserved: ' . $student->first_name . ' ' . $student->last_name;
            $g_event['colorId'] = 8;
        }
        else {
            $subscription = Subscription::where('id', $event->subscription_id)->with(
                [
                    'student',
                    'teacher',
                    'instrument',
                    'room',
                ]
            )->first();

            $g_event['name'] = $subscription->instrument->name . ' ' . $subscription->student->first_name . ' ' . $subscription->student->last_name;
            $g_event['description'] = "Student: " . ' ' . $subscription->student->first_name . ' ' . $subscription->student->last_name . "\n";
            $g_event['description'] .= "Room: " . ' ' . $subscription->room->name . "\n";
            $g_event['description'] .= "Instrument: " . ' ' . $subscription->instrument->name . "\n";
            $g_event['description'] .= "Status: " . ' ' . $event->status . "\n";

            $teacher = $subscription->teacher;
        }
        $g_event['startDateTime'] = Carbon::create($event->starting);
        $g_event['endDateTime'] = Carbon::create($event->ending);
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
        } catch (Exception $e) {
            // TODO: add this to error notifications
            return false;
        }
        return $g_event_response;
    }
    private function delete_google_event($event) {
        if(!is_null($event->google_event_id)) {
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
    private function update_google_event($event) {
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
                return $this->create_google_event($event);
            } catch (Exception $e) {
                // TODO: add this to error notifications
            }
        }
        return false;
    }

    public function schedule(Event $event) {
//        $this->validate([
//            'starting' => 'required',
//            'id' => 'required|exists:events,id'
//        ]);

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
        $ending_date->modify('+60 minutes');

        $event->status = 'scheduled';
        $event->starting = $starting_date;
        $event->ending = $ending_date;
        $event->save();

        $g_event_response = $this->create_google_event($event);
        $event->google_event_id = $g_event_response->id;
        $event->save();

        if(isset($_POST['recurrent']) && $_POST['recurrent'] === 'yes') {
            $other_events = Event::where(
                [
                    'subscription_id' => $event->subscription_id,
                    'status' => 'pending'
                ]
            )->get();

            $weeks_number = 1;
            foreach ($other_events as $event) {
                $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
                $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
                $ending_date->modify('+60 minutes');

                $starting_date->modify('+'.$weeks_number.' week');
                $ending_date->modify('+'.$weeks_number.' week');

                $event->status = 'scheduled';
                $event->starting = $starting_date;
                $event->ending = $ending_date;
                $event->save();

                $g_event_response = $this->create_google_event($event);
                $event->google_event_id = $g_event_response->id;
                $event->save();
                $weeks_number++;
            }
            // Reservations
            if(isset($_POST['timeslot_reservations']) && $_POST['timeslot_reservations'] === 'yes') {
                for($i = $weeks_number; $i < $weeks_number + 24; $i++) {
                    $reservation = new Reservation();
                    $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
                    $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
                    $ending_date->modify('+60 minutes');

                    $starting_date->modify('+'.$i.' week');
                    $ending_date->modify('+'.$i.' week');

                    $reservation->status = 'scheduled';
                    $reservation->starting = $starting_date;
                    $reservation->ending = $ending_date;
                    $reservation->student_id = $event->subscription->student_id;
                    $reservation->teacher_id = $event->subscription->teacher_id;
                    $reservation->room_id = $event->subscription->room_id;
                    $reservation->save();

                    $g_event_response = $this->create_google_event($reservation, true);
                    $reservation->google_event_id = $g_event_response->id;
                    $reservation->save();
                }
            }
        }

        return $event;
    }

    public function calendar_update(Event $event) {
//        $this->validate([
//            'starting' => 'required',
//            'id' => 'required|exists:events,id'
//        ]);

        $starting_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
        $ending_date = \DateTime::createFromFormat('d-m-Y H:i', $_POST['starting']);
        $ending_date->modify('+60 minutes');

        $event->starting = $starting_date;
        $event->ending = $ending_date;
        $event->save();

        $g_event_response = $this->update_google_event($event);
        $event->google_event_id = $g_event_response->id;
        $event->save();
        return $event;
    }
}
