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

        $event->subscription_id = 1;
        $event->starting = new \DateTime();
        $event->ending =  new \DateTime();

        $event->save();

    }
}
