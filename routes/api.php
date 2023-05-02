<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\CategoryController;

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

//api/v1
Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::controller(TransactionController::class)->group(function () {
        Route::get('/transactions/total_spent', 'getSpentValueByDateRange');
        Route::get('/transactions/new_merchants', 'getNewMerchants');
        Route::get('/transactions/total_roundup', 'getRoundUpTotal');
        
    });

    Route::apiResource('transactions', TransactionController::class);
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::controller(AccountController::class)->group(function () {
        Route::get('/accounts/net_value', 'getNetValue');
    });

    Route::apiResource('accounts', AccountController::class);
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('categories', CategoryController::class);
});