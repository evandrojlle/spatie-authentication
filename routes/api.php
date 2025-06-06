<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TenantResourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 */
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [RegisterController::class, 'create']);

    Route::middleware(['auth.jwt', 'IdentifyTenant'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('/tenant-resource', [TenantResourceController::class, 'index']);
        Route::get('me', [AuthController::class, 'me']);

        Route::get('/admin-resource', function () {
            return response()->json(['message' => 'Recurso protegido para administradores']);
        })->middleware('role:admin');

        Route::get('/user-resource', function () {
            return response()->json(['message' => 'Recurso protegido para usuários']);
        })->middleware('role:user');
    });
});
