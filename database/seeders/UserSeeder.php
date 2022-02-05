<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $user->api_token = Str::random(60);
        $user->save();
    }
}
