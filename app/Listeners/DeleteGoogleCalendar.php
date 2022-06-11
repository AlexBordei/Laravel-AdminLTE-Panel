<?php

namespace App\Listeners;

use App\Events\DeleteGoogleCalendarEvent;
use App\Models\Subscription;
use Google\Service\Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\GoogleCalendar\Event;

class DeleteGoogleCalendar
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DeleteGoogleCalendarEvent $event)
    {
        if(!is_null($event->event->google_event_id)) {
            try {
                $subscription = Subscription::where('id', $event->event->subscription_id)->with('teacher')->first();

                $g_event = null;
                if (!empty($subscription->teacher->google_calendar_id)) {
                    try {
                        $g_event = Event::find($event->event->google_event_id, $subscription->teacher->google_calendar_id);
                    } catch (Exception $e) {
                        $g_event = Event::find($event->event->google_event_id);
                    }
                } else {
                    $g_event = Event::find($event->event->google_event_id);
                }
                $g_event->delete();
            } catch (Exception $e) {
                // TODO: add this to error notifications
            }
        }
    }
}
