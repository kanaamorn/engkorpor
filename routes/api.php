<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// LINE OAuth callback
Route::post('/auth/line/callback', [LineAuthController::class, 'callback']);
