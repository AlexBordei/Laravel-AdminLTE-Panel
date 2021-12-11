<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentController;
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
});

require __DIR__.'/auth.php';


//TODO: Adauga buton de delete in formularele de edit
