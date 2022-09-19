<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Loan\LoanController;
use App\Http\Controllers\Api\Payment\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mini Aspire API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('loan', LoanController::class)->except(['create','destroy']);
    Route::apiResource('payment',PaymentController::class)->only(['index','show','update']);
    Route::patch('approve/loan/{id}',AdminController::class);

});


