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

