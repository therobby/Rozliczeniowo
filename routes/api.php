<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\UserController;

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


// Route::middleware()
Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/bill', [BillController::class, 'index']);
Route::middleware('auth:sanctum')->post('/bill', [BillController::class, 'store']);
Route::middleware('auth:sanctum')->get('/bill/{id}', [BillController::class, 'show']);
Route::middleware('auth:sanctum')->patch('/bill/{id}', [BillController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/bill/{id}', [BillController::class, 'destroy']);


