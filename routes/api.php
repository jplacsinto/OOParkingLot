<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EntrypointController;
use App\Http\Controllers\ParkingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('entrypoints', EntrypointController::class)->except([
    'edit', 'update'
]);

Route::controller(ParkingController::class)->prefix('parkings')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'park');
    Route::match(['put', 'patch'], '/{id}/unpark', 'unpark');
});

Route::fallback(function (){
    abort(404, 'API resource not found');
});