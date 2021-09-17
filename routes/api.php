<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillsGroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

Route::get('/roles', [HomeController::class, 'roles']);

Route::prefix('/user')->group(function() {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/logout', [UserController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::delete('/delete',[UserController::class, 'destroy']);
        Route::patch('/update',[UserController::class, 'update']);
        Route::get('/', [UserController::class, 'index']);
        Route::get('/id/{id}', [UserController::class, 'show']);
        Route::get('/find/{username}', [UserController::class, 'find']);
    });
});

Route::prefix('/group')->group(function(){
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('/', [BillsGroupController::class, 'index']); // will return user bills groups
        Route::post('/', [BillsGroupController::class, 'store']); // will create new bill group
        Route::delete('/{billgroup}', [BillsGroupController::class, 'destroy']); // will delete selected bill group
        Route::patch('/{billgroup}', [BillsGroupController::class, 'update']); // will update bill group with provided data
        Route::get('/{billgroup}', [BillsGroupController::class, 'show']); // get selected bill group data
        
        Route::get('/{billgroup}/bills', [BillController::class, 'groupBills']); // will return selected group bills
    
        Route::get('/{billgroup}/users', [BillsGroupController::class, 'getUsers']); // will return users with roles in group
        Route::post('/{billgroup}/user/{id}', [BillsGroupController::class, 'addUser']); // will add user to group
        Route::delete('/{billgroup}/user', [BillsGroupController::class, 'removeUser']); // will remove user from group
        Route::patch('/{billgroup}/user', [BillsGroupController::class, 'updateUserRole']); // will update user role in group
    });

});

Route::prefix('/bill')->group(function(){
    Route::middleware('auth:sanctum')->group(function(){
        // Route::get('/', [BillController::class, 'index']); // will return user bills
        Route::post('/{billgroup}', [BillController::class, 'store']); // will create new bill
        Route::get('/{bill}', [BillController::class, 'show']); // will show selected bill details
        Route::patch('/{bill}', [BillController::class, 'update']); // will update bill with provided data
        Route::delete('/{bill}', [BillController::class, 'destroy']); // will delete bill
        // Route::get('/group/{id}', [BillController::class, 'groupBills']); // will return selected group bills
        
        Route::get('/{bill}/products', [ProductController::class, 'billProducts']);
        Route::post('/{bill}/product', [ProductController::class, 'createBillProduct']);
        Route::patch('/{bill}/product/{product}', [ProductController::class, 'updateBillProduct']);
        Route::delete('/{bill}/product/{product}', [ProductController::class, 'deleteBillProducts']);
    });
});

