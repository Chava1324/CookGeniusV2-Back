<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/api/menus', function () {
    return response()->json(['message' => 'Ruta API funcionando correctamente 🚀']);
});

Route::post('/api/menus', [MenuController::class, 'generarMenu']);

Route::get('/test', function (Request $request) {
    return response()->json(['message' => 'Laravel API funcionando correctamente 🚀']);
});
