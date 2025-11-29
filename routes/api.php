<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Laravel API funcionando correctamente 🚀']);
});

// Ruta POST que consume el frontend
Route::post('/generar-menu', [MenuController::class, 'generarMenu']);




Route::get('/menus', [MenuController::class, 'index']);
Route::post('/menus', [MenuController::class, 'store']);
