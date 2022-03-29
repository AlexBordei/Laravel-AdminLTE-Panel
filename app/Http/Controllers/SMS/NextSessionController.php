<?php

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class NextSessionController extends Controller {

    public function render(View $view) {


        return new Response(
            view($view)
        );
    }
}
