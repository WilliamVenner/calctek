<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\CalcController\CalcController;

Route::get('/calc/add/{a}/{b}', [CalcController::class, 'add']);
Route::get('/calc/sub/{a}/{b}', [CalcController::class, 'sub']);
Route::get('/calc/mul/{a}/{b}', [CalcController::class, 'mul']);
Route::get('/calc/div/{a}/{b}', [CalcController::class, 'div']);
Route::get('/calc/eval/{expr}', [CalcController::class, 'eval'])
    ->where(['expr' => '.+']); // Accept any input for the expr parameter
