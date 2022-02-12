<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return $this->buildResponse('calendar.list');
    }
}
//TODO: each teacher will have his own calendar
//TODO: when scheduling events a check for invinite events will make event go for 2 years, recurrent
