<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;

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

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::get('get-all', 'index');
    Route::get('search/{usename}/{password}', 'search');
    Route::post('create', 'store');
    Route::post('update/{id}', 'update');
});

Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
    Route::get('search-userid/{user_id}', 'show');
    Route::post('create', 'store');
    Route::get('update/{id}', 'update');
    Route::post('update-data/{id}', 'updateData');
    Route::get('get-by-id/{id}', 'getById');
    Route::get('filter-by-description/{user_id}/{description}', 'filters');
});