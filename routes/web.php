<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimestampController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TimestampController::class, 'index'])->name('home');
Route::post('/store', [TimestampController::class, 'store'])->name('timestamps-submit');