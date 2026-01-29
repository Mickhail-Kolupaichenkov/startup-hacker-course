<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [HomeController::class, 'about']);
Route::get('/dashboard', [HomeController::class, 'dashboard']);
Route::get('/profile', [HomeController::class, 'profile']);
Route::get('/scrollpage', [HomeController::class, 'scrollpage']);

Route::post('/submit-form', [HomeController::class, 'submitForm']);
