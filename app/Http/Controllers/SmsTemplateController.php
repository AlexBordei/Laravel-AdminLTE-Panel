<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Service;
use App\Models\Sms;
use App\Models\SmsTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
