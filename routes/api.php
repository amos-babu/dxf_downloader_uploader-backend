<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/upload_file', [FilesController::class, 'upload'])->middleware('auth:sanctum');

Route::get('/retrieve_files', [FilesController::class, 'index']);
Route::get('/file_display/{id}', [FilesController::class, 'fileDisplay']);
Route::get('/download_dxf/{id}', [FilesController::class, 'downloadDxf']);
Route::get('/download_image/{id}', [FilesController::class, 'downloadImage']);
