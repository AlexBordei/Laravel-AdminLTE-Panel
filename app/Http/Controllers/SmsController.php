<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Sms;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            $actual_date = Carbon::now();
            $actual_date = $actual_date->subSeconds(20);

            $sms->sms_service_status = "Bad. Service is not responding";
            if($sms_service->last_seen > $actual_date) {
                $sms->sms_service_status = "All good. Service is up";
            }
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

        return redirect('/sms')->with('success', 'SMS request has been posted successfully!');
    }

    public function resend(Sms $sms)
    {
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
