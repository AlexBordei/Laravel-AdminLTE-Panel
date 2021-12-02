<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\StudentController;
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
});

require __DIR__.'/auth.php';
