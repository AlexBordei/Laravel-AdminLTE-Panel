<?php


use App\Http\Controllers\API\APISmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/sms/{sms}', [APISmsController::class, 'update']);
Route::middleware('auth:api')->get('/sms', [APISmsController::class, 'index']);
Route::middleware('auth:api')->post('/service/sms', [APISmsController::class, 'serviceStatus']);
