<?php

namespace App\Console\Commands;

use App\Models\Sms;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now();
        $date = $date->subMinutes(10);

        print($date);
        $sms = Sms::where(['status' => 'pending'])
            ->where('updated_at', '<', $date)
            ->get();

        foreach ($sms as $message) {
            $message->update(
                ['status' => 'error',
                    'error' => 'Timeout'
                ]
            );
        }

        return Command::SUCCESS;
    }
}
