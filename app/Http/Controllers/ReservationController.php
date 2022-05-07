<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Subscription;
use Google\Service\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reservation $reservation)
    {
        try {
            $this->delete_google_event($reservation);
            $reservation->deleteOrFail();
            return redirect()
                ->back()
                ->with('success', 'Reservation canceled successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['msg' => 'There was an error canceling the event!']);
        }
    }


    public function deleteAllReservations() {
        $response = [
            'error' => true,
        ];

        if(isset($_POST['student_id']) && is_numeric($_POST['student_id'])) {
           $reservations = Reservation::where('student_id', $_POST['student_id'])->get();

           foreach ($reservations as $reservation) {
               $this->delete_google_event($reservation);
               $reservation->deleteOrFail();
           }
            $response['error'] = false;
            $response['message'] = 'Success!';
        }

        return new Response($response);
    }

    private function delete_google_event($reservation) {
        if(!is_null($reservation->google_event_id)) {
            try {
                $subscription = Subscription::where('id', $reservation->subscription_id)->with('teacher')->first();

                $g_event = null;
                if (!empty($subscription->teacher->google_calendar_id)) {
                    try {
                        $g_event = \Spatie\GoogleCalendar\Event::find($reservation->google_event_id, $subscription->teacher->google_calendar_id);
                    } catch (Exception $e) {
                        $g_event = \Spatie\GoogleCalendar\Event::find($reservation->google_event_id);
                    }
                } else {
                    $g_event = \Spatie\GoogleCalendar\Event::find($reservation->google_event_id);
                }
                $g_event->delete();
                return true;
            } catch (Exception $e) {
                // TODO: add this to error notifications
            }
        }
        return false;
    }
}
