<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSMSTemplateRequest;
use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

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
        $path = app_path() . "/Models";
        $models = GeneralModelsController::getModels($path);
        $models = array_map(function($model) use ($path) {
            return str_replace($path . '/', '', $model);
        }, (array)$models );

        return $this->buildResponse('sms_template.create', ['models' => $models]);
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
