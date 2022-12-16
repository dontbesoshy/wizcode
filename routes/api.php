<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ReservationController;
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


Route::post('login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::resource('reservation', ReservationController::class)->only(['index', 'store', 'destroy']);
});