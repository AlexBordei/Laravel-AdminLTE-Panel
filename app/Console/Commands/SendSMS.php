<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use \PhpMqtt\Client\MqttClient;


class SendSMS extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SMS:send
                        {phone_number : The phone number}
                        {message : The message to be sent}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends an SMS to a phone number';

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
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = rand(5, 15);
        $clean_session = false;

        $connectionSettings  = new ConnectionSettings();
        $connectionSettings
            ->setKeepAliveInterval(60)
            ->setLastWillQualityOfService(1);

        $phone_number = $this->argument('phone_number');
        $message = $this->argument('message');

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, $clean_session);
        printf("client connected\n");

        $mqtt->publish(
        // topic
            env('SMS_SEND_TOPIC', '/panel/sms'),
            // payload
            $phone_number . '#' . $message,
            // qos
            0,
            // retain
            true
        );
        sleep(1);

        $mqtt->disconnect();

        return Command::SUCCESS;
    }
}
