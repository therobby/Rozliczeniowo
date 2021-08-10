<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;

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


Route::middleware('auth:api')->get('/bill', [BillController::class, 'index']);
Route::middleware('auth:api')->post('/bill', [BillController::class, 'store']);
Route::middleware('auth:api')->get('/bill/{id}', [BillController::class, 'show']);
Route::middleware('auth:api')->patch('/bill/{id}', [BillController::class, 'update']);
Route::middleware('auth:api')->delete('/bill/{id}', [BillController::class, 'destroy']);


