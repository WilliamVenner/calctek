<?php

use App\Http\Controllers\ProfileController;
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

use App\Http\Controllers\CalcController\CalcController;

Route::get('/', [CalcController::class, 'index'])->name('calculator');

Route::get('/calc/add/{a}/{b}', [CalcController::class, 'add'])->name('calculator.add');
Route::get('/calc/sub/{a}/{b}', [CalcController::class, 'sub'])->name('calculator.sub');
Route::get('/calc/mul/{a}/{b}', [CalcController::class, 'mul'])->name('calculator.mul');
Route::get('/calc/div/{a}/{b}', [CalcController::class, 'div'])->name('calculator.div');
Route::get('/calc/eval/{expr}', [CalcController::class, 'eval'])
    ->where(['expr' => '.+']) // Accept any input for the expr parameter
    ->name('calculator.eval');

Route::middleware('auth')->group(function () {
    Route::get('/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/account', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/account', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
