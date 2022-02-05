<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Sms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class APISmsController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sms $sms)
    {
        $validate = $request->validate([
            'status' => 'in:pending,sent,error'
        ]);

        $sms->update($request->all());

        return new Response($sms, 200);
    }

    public function serviceStatus(Request $request) {
        $SMSService = Service::where('name', 'sms_service')->first();

        if(empty($SMSService)) {
            $SMSService = Service::create(
                [
                    'name' => 'sms_service',
                    'last_seen' => Carbon::now()
                ]
            );
        } else {
            $SMSService->update(
                [
                    'last_seen' => Carbon::now()
                ]
            );
        }
        return new Response($SMSService);
    }

}
