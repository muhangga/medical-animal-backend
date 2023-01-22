<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\ClinicController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/clinics', [ClinicController::class, 'index']);
Route::post('/clinic', [ClinicController::class, 'store']);
Route::get('/clinic/{id}', [ClinicController::class, 'show']);
Route::put('/clinic/{id}', [ClinicController::class, 'update']);
Route::delete('/clinic/{id}', [ClinicController::class, 'destroy']);
Route::GET('/near-clinics', [ClinicController::class, 'nearLocation']);
Route::POST('/near-clinics', [ClinicController::class, 'nearLocation']);
Route::GET('/near-clinic/{id}', [ClinicController::class, 'nearLocationById']);

// search clinic
Route::GET('/search-clinic/{name}', [ClinicController::class, 'searchClinic']);
Route::GET('/clinics', [ClinicController::class, 'fecthAllClinic']);
Route::GET('/clinics/{page}', [ClinicController::class, 'fetchClinicPerPage']);
Route::POST('/user-request', [ClinicController::class, 'storeUserRequest']);

Route::get('/', function() {
    $p = Redis::incr('page');
    return "Page has been visited $p times";
});


Route::get("users_with_cache", "ClinicController@getClinicRedis");
Route::get("users_with_query", "ClinicController@getUser");
