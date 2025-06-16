<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\orderController;
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
Route::get('/order/by-date/{date}',[orderController::class,'getOrderByDate']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
