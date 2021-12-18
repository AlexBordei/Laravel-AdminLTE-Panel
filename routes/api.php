<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\GoogleCalendar\Event;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test', function(){
    $event = new Event;

    $event->name = 'A new event';
    $event->startDateTime = Carbon\Carbon::now();
    $event->endDateTime = Carbon\Carbon::now()->addHour();

    $event->save();

    var_dump($event);

    return;
});
