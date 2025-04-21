<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RecommendationController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::resource('questions', QuestionController::class);    
    Route::resource('menu_completo', MenuController::class);
    Route::resource('answers', AnswerController::class);    
    Route::resource('recommendations', RecommendationController::class);
    Route::get('get_csrf', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
});

