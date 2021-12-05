<?php

namespace Database\Seeders;

use App\Models\Room;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        for($i = 0; $i < 100; $i++) {
            $room = new Room();
            $room->name = $faker->name();
            $room->instrument_ids = '[]';
            $room->save();
        }
    }
}
