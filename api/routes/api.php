<?php

use App\Http\Controllers\CigarController;
use App\Http\Controllers\HumidorCigarController;
use App\Http\Controllers\HumidorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GeneralImageController;

use Illuminate\Support\Facades\Route;

Route::get('/user', [UserController::class, 'user'])->name('get-user')->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Cigar Logs
    Route::get('/cigars/{cigar_id}', [CigarController::class, 'show'])->name('get-cigar');
    Route::get('/cigars', [CigarController::class, 'index'])->name('get-cigars');
    Route::post('/cigars', [CigarController::class, 'store'])->name('store-cigar');
    Route::patch('/cigars/{cigar_id}', [CigarController::class, 'update'])->name('update-cigar');
    Route::delete('/cigars/{cigar_id}', [CigarController::class, 'delete'])->name('delete-cigar');

    // Humidors
    Route::get('/humidors', [HumidorController::class, 'index'])->name('get-humidors');
    Route::get('/humidors/{humidor_id}', [HumidorController::class, 'show'])->name('get-humidor');
    Route::post('/humidors', [HumidorController::class, 'store'])->name('store-humidor');
    Route::patch('/humidors/{humidor_id}', [HumidorController::class, 'update'])->name('update-humidor');
    Route::delete('/humidors/{humidor_id}', [HumidorController::class, 'delete'])->name('delete-humidor');

    // Humidor Cigars
    Route::get('/humidors/{humidor_id}/cigars', [HumidorCigarController::class, 'index'])->name('get-humidor-cigars');
    Route::post('/humidors/{humidor_id}/cigars', [HumidorCigarController::class, 'store'])->name('store-humidor-cigar');
    Route::patch('/humidors/{humidor_id}/cigars/{humidor_cigar_id}', [HumidorCigarController::class, 'update'])->name('update-humidor-cigar');
    Route::delete('/humidors/{humidor_id}/cigars/{humidor_cigar_id}', [HumidorCigarController::class, 'delete'])->name('delete-humidor-cigar');

    // Image Routes
    Route::post('/image/upload/general', [GeneralImageController::class, 'upload'])->name('image-upload-general');
});