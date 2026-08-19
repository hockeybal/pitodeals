<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/content', ContentController::class);
Route::get('/municipalities', [ContentController::class, 'municipalities']);

Route::middleware('throttle:20,1')->group(function (): void {
    Route::post('/leads', [LeadController::class, 'store']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::post('/contact', [ContactController::class, 'store']);
});
