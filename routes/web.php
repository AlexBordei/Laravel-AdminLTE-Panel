<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SmsTemplateController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubscriptionTypeController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return redirect('dashboard');
})->middleware(['auth'])->name('home');

Route::get('/dashboard', [AppController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('/student', StudentController::class);
    Route::resource('/teacher', TeacherController::class);
    Route::resource('/room', RoomController::class);
    Route::resource('/instrument', InstrumentController::class);
    Route::resource('/event', EventController::class);
    Route::post('/event/schedule/{event}', [EventController::class, 'schedule']);

    Route::resource('/subscription', SubscriptionController::class);
    Route::resource('/subscription_type', SubscriptionTypeController::class);
    Route::resource('/payment', PaymentController::class);
    Route::resource('/calendar', CalendarController::class);
    Route::resource('/sms', SmsController::class);
    Route::resource('/sms_template', SmsTemplateController::class);
    Route::post('/sms/resend/{sms}', [SmsController::class, 'resend']);
});

require __DIR__.'/auth.php';
