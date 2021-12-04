<?php

namespace Database\Seeders;

use App\Models\Student;
use Faker\Factory;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
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
            $student = new Student();
            $student->first_name = $faker->firstname();
            $student->last_name = $faker->lastName();
            $student->phone = $faker->phoneNumber();
            $student->email = $faker->email();
            $student->birth_date = $faker->dateTimeBetween('-18 years');
            $student->save();
        }
    }
}
