<?php

namespace App\Listeners;

use App\Events\DeleteReservationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Subscription;
use Spatie\GoogleCalendar;

class DeleteReservation
{
    //TODO:: queues
    /**
     * Handle the event.
     *
     * @param DeleteReservationEvent $event
     * @return void
     */
    public function handle(DeleteReservationEvent $event)
    {
        $subscription = Subscription::where('id', $event->reservation->subscription_id)->with('teacher')->first();

        $g_event = null;
        if (! empty($subscription->teacher->google_calendar_id)) {
            try {
                $g_event = GoogleCalendar\Event::find($event->reservation->google_event_id, $event->reservation->teacher->google_calendar_id);
            } catch (Exception $e) {
                $g_event = GoogleCalendar\Event::find($event->reservation->google_event_id);
            }
        } else {
            $g_event = GoogleCalendar\Event::find($event->reservation->google_event_id);
        }
        $g_event->delete();
    }
}
