<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestClinicController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get("users_with_cache", [TestClinicController::class, 'index']);
Route::get("users_with_query", [TestClinicController::class, 'getUser']);
