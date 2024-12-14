<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/upload_file', [FilesController::class, 'upload']);
    Route::get('/user', [AuthController::class, 'authenticatedUser']);
    Route::put('/update_user', [AuthController::class, 'updateUserProfile']);
});

Route::put('/update_user', [AuthController::class, 'updateUserProfile']);
Route::get('/user_details/{id}', [AuthController::class, 'userDetails']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/retrieve_files', [FilesController::class, 'index']);
Route::get('/search', [FilesController::class, 'search']);
Route::get('/file_display/{id}', [FilesController::class, 'fileDisplay']);
Route::get('/download_dxf/{id}', [FilesController::class, 'downloadDxf']);
Route::get('/download_image/{id}', [FilesController::class, 'downloadImage']);
