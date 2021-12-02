<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Alex Bordei';
        $user->email = 'alex@whiz.ro';
        $user->password = Hash::make('12qwaszx');
        $user->save();
    }
}
