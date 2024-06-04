<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GeneralController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'getUsers'])->name('getUsers');
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/getRumahMakan', [GeneralController::class, 'getRumahMakan'])->name('getRumahMakan');
    Route::post('/detailRumahMakan', [GeneralController::class, 'detailRumahMakan'])->name('detailRumahMakan');
    Route::post('/listCommentById', [GeneralController::class, 'listCommentById'])->name('listCommentById');
    Route::post('/addCommentById', [GeneralController::class, 'addCommentById'])->name('addCommentById');
    Route::post('/rate', [GeneralController::class, 'rate'])->name('rate');
    Route::post('/add-RumahMakan', [GeneralController::class, 'addRumahMakan'])->name('addRumahMakan');
    Route::post('/update-RumahMakan', [GeneralController::class, 'updateRumahMakan'])->name('updateRumahMakan');
    Route::post('/delete-RumahMakan', [GeneralController::class, 'deleteRumahMakan'])->name('deleteRumahMakan');
    // Route::post('/deleteComment', [GeneralController::class, 'deleteComment'])->name('deleteComment');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
