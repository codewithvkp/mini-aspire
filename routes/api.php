<?php

use App\Http\Controllers\LoanApplicationController;
use App\Http\Controllers\LoanRepaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Auth routes

require __DIR__ . '/auth.api.php';

// Loan application routes

Route::post('loan-applications/{loan_application}/approve', [
    LoanApplicationController::class, 'approve'
]);
Route::apiResource('loan-applications', LoanApplicationController::class);

// Loan repayments

Route::get('loan-applications/{loan_application}/repayments', [
    LoanRepaymentController::class, 'index'
]);
Route::post('loan-repayments/{loan_repayment}/repay', [
    LoanRepaymentController::class, 'repay'
]);
