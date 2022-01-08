<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new UserSeeder())->run();
        (new MenuSeeder())->run();
        (new MenuOptionSeeder())->run();
        (new StudentSeeder())->run();
        (new InstrumentSeeder())->run();
        (new RoomSeeder())->run();
        (new TeacherSeeder())->run();
        (new EventSeeder())->run();
        (new SubscriptionTypeSeeder())->run();
        (new PaymentSeeder())->run();
        (new SubscriptionSeeder())->run();
    }
}
