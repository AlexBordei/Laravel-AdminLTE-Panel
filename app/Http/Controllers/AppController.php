<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function home() {
        return $this->buildResponse('dashboard');
    }

    public function dashboard() {
        return $this->buildResponse('dashboard');
    }
}
