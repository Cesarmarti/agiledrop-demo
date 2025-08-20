<?php

use App\Http\Controllers\ApiAuthContoller;
use App\Http\Controllers\MediaFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register',[ApiAuthContoller::class,'register']);
Route::post('login',[ApiAuthContoller::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',[ApiAuthContoller::class,'logout']);

    Route::get('media-files',[MediaFileController::class,'getAllFiles']);
    Route::post('media-files',[MediaFileController::class,'uploadFile']);
    Route::get('media-files/{id}',[MediaFileController::class,'getFileInfo']);
    Route::get('media-files/{id}/download',[MediaFileController::class,'downloadFile']);
    Route::delete('media-files/{id}',[MediaFileController::class,'deleteFile']);
});

