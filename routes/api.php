<?php

use App\Http\Controllers\ForMesinController;
use App\Models\Mesin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('esp32')->group(function () {
    Route::get('mesin', [ForMesinController::class, 'mesin']);
    Route::get('siswa', [ForMesinController::class, 'siswa']);
});
Route::post('absensi', [ForMesinController::class, 'absensi']);
