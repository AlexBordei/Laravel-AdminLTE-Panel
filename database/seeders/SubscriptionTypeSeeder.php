<?php

namespace Database\Seeders;

use App\Models\SubscriptionType;
use Illuminate\Database\Seeder;

class SubscriptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscription_type = new SubscriptionType();
        $subscription_type->name = 'Testing';
        $subscription_type->price = 70;
        $subscription_type->sessions_number = 1;
        $subscription_type->duration = 0.5;
        $subscription_type->instruments_number = 1;
        $subscription_type->students_number = 1;
        $subscription_type->sessions_per_week = 1;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = '1 lesson';
        $subscription_type->price = 130;
        $subscription_type->sessions_number = 1;
        $subscription_type->duration = 1;
        $subscription_type->instruments_number = 1;
        $subscription_type->students_number = 1;
        $subscription_type->sessions_per_week = 1;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = '4 lessons';
        $subscription_type->price = 440;
        $subscription_type->sessions_number = 4;
        $subscription_type->duration = 1;
        $subscription_type->instruments_number = 1;
        $subscription_type->sessions_per_week = 1;
        $subscription_type->students_number = 1;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = '8 sessions';
        $subscription_type->price = 800;
        $subscription_type->sessions_number = 8;
        $subscription_type->duration = 1;
        $subscription_type->instruments_number = 2;
        $subscription_type->students_number = 1;
        $subscription_type->sessions_per_week = 2;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = '4 lessons weekend';
        $subscription_type->price = 480;
        $subscription_type->sessions_number = 4;
        $subscription_type->duration = 1;
        $subscription_type->instruments_number = 1;
        $subscription_type->sessions_per_week = 1;
        $subscription_type->students_number = 1;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = '8 sessions weekend';
        $subscription_type->price = 880;
        $subscription_type->sessions_number = 8;
        $subscription_type->duration = 0.5;
        $subscription_type->instruments_number = 1;
        $subscription_type->students_number = 1;
        $subscription_type->sessions_per_week = 2;
        $subscription_type->save();

        $subscription_type = new SubscriptionType();
        $subscription_type->name = 'Band';
        $subscription_type->price = 240;
        $subscription_type->sessions_number = 4;
        $subscription_type->duration = 1.5;
        $subscription_type->instruments_number = 1;
        $subscription_type->students_number = 1;
        $subscription_type->save();

    }
}
