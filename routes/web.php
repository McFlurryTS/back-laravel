<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return view('welcome');
});

// Registro y login
Route::post('/login', [LoginController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas con auth:sanctum
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout']);

    // Perfil del usuario autenticado
    Route::get('/perfil', function (Request $request) {
        return $request->user();
    });

    // Recursos protegidos
    Route::resource('questions', QuestionController::class);
    Route::resource('menus', MenuController::class);
});
