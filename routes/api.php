<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroqController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/groq/models', [GroqController::class, 'listModels']);
Route::post('/groq/test-chat', [GroqController::class, 'testChat']);
Route::get('/groq/test-chat', [GroqController::class, 'testChat']);
