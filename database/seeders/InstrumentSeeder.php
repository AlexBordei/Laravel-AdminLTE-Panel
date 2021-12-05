<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instruments_names = [
            'Cor', 'Productie muzicala', 'Tobe/Percutie', 'Bass', 'Chitara', 'Pian', 'Canto', 'Trupa'
        ];

        foreach ($instruments_names as $instrument_name) {
            $instrument = new Instrument();
            $instrument->name = $instrument_name;
            $instrument->save();
        }

    }
}
