<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Room;
use App\Models\Service;
use App\Models\Sms;
use Illuminate\Http\Request;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sms = Sms::all();

        $sms_service = Service::where('name', 'sms_service')->first();
        if(!empty($sms_service)) {
            $sms->sms_service_status = $sms_service->last_seen;
        }

        return $this->buildResponse('sms.list', $sms);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildResponse('sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'to' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'message' => 'required|max:255',
        ]);

        $sms = Sms::create([
            'from' => env('SMS_FROM_NUMBER', ''),
            'to' => $request->to,
            'message' => $request->message,
            'status' => 'pending'
        ]);
        // TODO: clear this in a more optimal class
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = rand(5, 15);
        $clean_session = false;

        $connectionSettings  = new ConnectionSettings();
        $connectionSettings
            ->setKeepAliveInterval(60)
            ->setLastWillQualityOfService(1);

        $phone_number = $request->to;
        $message = $request->message;

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, $clean_session);

        $mqtt->publish(
        // topic
            env('SMS_SEND_TOPIC', '/panel/sms'),
            // payload
            $sms->id . '#' . $phone_number . '#' . $message,
            // qos
            0,
            // retain
            true
        );
        sleep(1);

        $mqtt->disconnect();

        return redirect('/sms')->with('success', 'SMS request has been posted successfully!');
    }

    public function resend(Sms $sms)
    {
        // TODO: clear this in a more optimal class
        $server   = 'broker.hivemq.com';
        $port     = 1883;
        $clientId = rand(5, 15);
        $clean_session = false;

        $connectionSettings  = new ConnectionSettings();
        $connectionSettings
            ->setKeepAliveInterval(60)
            ->setLastWillQualityOfService(1);

        $phone_number = $sms->to;
        $message = $sms->message;

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, $clean_session);

        $mqtt->publish(
        // topic
            env('SMS_SEND_TOPIC', '/panel/sms'),
            // payload
            $sms->id . '#' . $phone_number . '#' . $message,
            // qos
            0,
            // retain
            true
        );
        sleep(1);

        $mqtt->disconnect();

        $sms->update(['status' => 'pending', 'error' => '']);

        return back()->with('success', 'SMS request has been posted successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function show(Sms $sms)
    {
        return $this->buildResponse('sms.single', $sms);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function edit(Sms $sms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sms $sms)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sms $sms)
    {
        //
    }
}
