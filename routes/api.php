<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\orderController;
use App\Http\Controllers\ServiceController;
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
Route::get('getOrder',[
    orderController::class,
    'index'
]);
Route::post('/createOrder',[orderController::class, 'createOrder']);

Route::get('/order/by-user/{id}',[orderController::class,'getOrderByUser']);
Route::get('/order/by-vehicle/{id}',[orderController::class,'getOrderByVehicle']);
Route::get('/order/by-date/{created_at}',[orderController::class,'getOrderByDate']);

Route::get('/service/get',[ServiceController::class,'index']);
Route::post('/service/store',[ServiceController::class,'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
