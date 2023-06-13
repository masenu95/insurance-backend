<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UploadController;
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

// // Agent Only
Route::middleware('auth:sanctum')->group(function () {

   Route::post('/create-motor-detail',[InsuranceController::class,'createMotor']);

   Route::resource('customer',CustomerController::class);
});


Route::get('insurance-type',[InsuranceController::class,'getInsuranceType']);

Route::get('insurance-product/{id}',[InsuranceController::class,'getInsuranceProduct']);


Route::get('insurance-coverage/{id}',[InsuranceController::class,'getInsuranceCoverage']);

Route::get('regions',[InsuranceController::class,'getRegion']);
Route::get('get-district/{id}',[InsuranceController::class,'district']);

Route::post('upload-image',[UploadController::class,'uploadMotorImage']);



Route::post('mobile-payment',[PaymentController::class,'mobile']);


