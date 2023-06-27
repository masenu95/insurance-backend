<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimsNotificationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UploadController;
use App\Models\ClaimNotification;
use Faker\Provider\ar_EG\Company;
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

   Route::resource('company',CompanyController::class);

   Route::get('insurance-type',[InsuranceController::class,'getInsuranceType']);

Route::get('insurance-product/{id}',[InsuranceController::class,'getInsuranceProduct']);


Route::get('insurance-coverage/{id}',[InsuranceController::class,'getInsuranceCoverage']);

Route::get('regions',[InsuranceController::class,'getRegion']);
Route::get('get-district/{id}',[InsuranceController::class,'district']);

Route::post('upload-image',[UploadController::class,'uploadMotorImage']);

Route::post('get-insurance-value',[InsuranceController::class,'getInsuranceValue']);



Route::post('mobile-payment',[PaymentController::class,'mobile']);

Route::resource('motor',MotorController::class);

Route::resource('agent',AgentController::class);

Route::post('add-product',[CompanyController::class,'addProduct']);

Route::get('insurance-company/{id}',[CompanyController::class,'getCompanyByType']);

Route::post('create-notification',[ClaimsNotificationController::class,'create']);

Route::get('get-notification/{id}',[ClaimsNotificationController::class,'show']);
});






Route::post('register', [AuthController::class,'register']);


Route::post('get-Token', [AuthController::class,'getToken']);

