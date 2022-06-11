<?php

namespace App\Http\Controllers;

use App\Events\DeleteGoogleCalendarEvent;
use App\Models\Reservation;
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
            event(new DeleteGoogleCalendarEvent($reservation));
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
               event(new DeleteGoogleCalendarEvent($reservation));
               $reservation->deleteOrFail();
           }
            $response['error'] = false;
            $response['message'] = 'Success!';
        }

        return new Response($response);
    }
}
