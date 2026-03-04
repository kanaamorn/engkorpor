<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineWebhookController;
// use App\Http\Controllers\LineAuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



// LINE OAuth callback
// Route::post('/line/webhook', [LineAuthController::class, 'callback']);
Route::post('/line/callback', function (Request $request) {
    return response()->json(['status' => '200']);
    // Handle LINE OAuth callback logic here
    // You can exchange the code for an access token and retrieve user info
    // Then, you can create or update the user in your database
    // Finally, you can return a response or redirect as needed
}   );

Route::post('/line/webhook', [LineWebhookController::class, 'handle']);


