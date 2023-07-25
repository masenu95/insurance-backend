<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/{vue?}', function () {
    return view('welcome');
})->where('vue', '[\/\w\.-]*');

Route::post('success-transaction/{id}',function () {
    return view('welcome');
});

Route::post('success-transaction/{id}',function () {
    return view('welcome');
});
