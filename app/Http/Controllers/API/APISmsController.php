<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sms;
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

}
