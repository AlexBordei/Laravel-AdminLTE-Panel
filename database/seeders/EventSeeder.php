<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = new Event();

        $event->student_id = 1;
        $event->teacher_id = 2;
        $event->instrument_id = 3;
        $event->room_id = 4;
        $event->starting = new \DateTime();
        $event->ending =  new \DateTime();

        $event->save();

    }
}
