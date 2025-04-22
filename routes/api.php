<?php

use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RecommendationController;

/*
|--------------------------------------------------------------------------
| Rutas públicas (no requieren autenticación)
|--------------------------------------------------------------------------
*/

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
Route::resource('menus', MenuController::class);

Route::middleware(['auth:sanctum'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout']);

    // Perfil del usuario autenticado
    Route::get('/perfil', function (Request $request) {
        return $request->user();
    });

    // Recursos protegidos
    Route::resource('questions', QuestionController::class);
    Route::resource('menu_completo', MenuController::class);
    Route::resource('answers', AnswerController::class);    
    Route::resource('recommendations', RecommendationController::class);
    Route::get('get-recommendation', 'App\Http\Controllers\RecommendationController@getRecommendation');
});
