<?php

use App\Http\Controllers\InsuranceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('insurance-type',[InsuranceController::class,'getInsuranceType']);

Route::get('insurance-product/{id}',[InsuranceController::class,'getInsuranceProduct']);


Route::get('insurance-coverage/{id}',[InsuranceController::class,'getInsuranceCoverage']);
