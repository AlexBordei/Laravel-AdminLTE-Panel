<?php

namespace Database\Seeders;

use App\Models\Instrument;
use App\Models\Room;
use App\Models\Teacher;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
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
            $teacher = new Teacher();
            $teacher->first_name = $faker->firstname();
            $teacher->last_name = $faker->lastName();
            $teacher->phone = $faker->phoneNumber();
            $teacher->email = $faker->email();
            $teacher->birth_date = $faker->dateTimeBetween('-50 years');
            $teacher->google_calendar_id = $faker->uuid();
            $teacher->room_id = rand(1, count(Room::all()));
            $teacher->instrument_ids = json_encode([0,1,2,3,4,5,6,7]);
            $teacher->save();
        }
    }
}
