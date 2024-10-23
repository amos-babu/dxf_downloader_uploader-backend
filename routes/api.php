<?php

use App\Http\Controllers\FilesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/retrieve_files', [FilesController::class, 'index']);
Route::get('/file_display/{id}', [FilesController::class, 'fileDisplay']);
Route::post('/upload_file', [FilesController::class, 'upload']);
Route::get('/download_files/{id}', [FilesController::class, 'download']);
