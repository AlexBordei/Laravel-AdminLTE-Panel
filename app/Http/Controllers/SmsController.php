<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Sms;
use App\Models\SmsTemplate;
use App\Models\Student;
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
        $students = Student::all();
        $smsTemplates = SmsTemplate::all();
        return $this->buildResponse('sms.create', array('students' => $students, 'sms_templates' => $smsTemplates));
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
            'student_id' => 'exists:students,id',
            'message' => 'required_without:sms_template_id|max:255',
            'sms_template_id' => 'exists:sms_templates,id',
        ]);

        $student = Student::where('id', $request->student_id)->first();

        $message = '';

        if(empty($request->message)) {
            $smsTemplate = SmsTemplate::where('id', $request->sms_template_id)->first();

            $message = view($smsTemplate->view, ['student' => $student])->render();
        } else {
            $message = $request->message;
        }

        $sms = Sms::create([
            'from' => env('SMS_FROM_NUMBER', ''),
            'to' => $student->phone,
            'message' => $message,
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
