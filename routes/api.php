<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillsGroupController;
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

Route::prefix('/user')->group(function() {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::delete('/delete',[UserController::class, 'destroy']);
        Route::patch('/update',[UserController::class, 'update']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/get', [UserController::class, 'index']);
        Route::get('/get/{id}', [UserController::class, 'show']);
        Route::get('/find/{username}', [UserController::class, 'find']);
    });
});

Route::prefix('/group')->group(function(){
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/', [BillsGroupController::class, 'index']); // will return user bills groups
        Route::post('/', [BillsGroupController::class, 'store']); // will create new bill group
        Route::delete('/{id}', [BillsGroupController::class, 'destroy']); // will delete selected bill group
        Route::patch('/{id}', [BillsGroupController::class, 'update']); // will update bill group with provided data
        Route::get('/{id}', [BillsGroupController::class, 'show']); // get selected bill group data
        
        Route::get('/{id}/bills', [BillController::class, 'groupBills']); // will return selected group bills
    });

});

Route::prefix('/bill')->group(function(){
    Route::middleware('auth:sanctum')->group(function(){
        // Route::get('/', [BillController::class, 'index']); // will return user bills
        Route::post('/{id}', [BillController::class, 'store']); // will create new bill
        Route::get('/{id}', [BillController::class, 'show']); // will show selected bill details
        Route::patch('/{id}', [BillController::class, 'update']); // will update bill with provided data
        Route::delete('/{id}', [BillController::class, 'destroy']); // will delete bill
        // Route::get('/group/{id}', [BillController::class, 'groupBills']); // will return selected group bills
        Route::get('/{id}/products', [BillController::class, 'billProducts']);
        Route::post('/{bill_id}/product', [BillController::class, 'createBillProduct']);
        Route::patch('/{bill_id}/product/{product_id}', [BillController::class, 'updateBillProduct']);
        Route::delete('/{bill_id}/product/{product_id}', [BillController::class, 'deleteBillProducts']);
    });
});

