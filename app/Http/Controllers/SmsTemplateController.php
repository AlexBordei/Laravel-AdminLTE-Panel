<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSMSTemplateRequest;
use App\Models\SmsTemplate;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sms_templates = SmsTemplate::all();

        return $this->buildResponse('sms_template.list', $sms_templates);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->buildResponse('sms_template.create');
    }

    public function store(StoreSMSTemplateRequest $request) {
        $request->validate([
            'name' => 'required',
            'message' => 'required|max:255',
            'view' => 'required',
        ]);

        SmsTemplate::create([
            'name' => $request->name,
            'message' => $request->message,
            'view' => $request->view,
        ]);

        return redirect('/sms_template')->with('success', 'SMS Template have been successfully saved!');
    }
}
