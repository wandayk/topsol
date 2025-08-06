<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\FinancialController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '2.0.0'
    ]);
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Clients
    Route::apiResource('clients', ClientController::class);
    Route::get('/clients/search/{term}', [ClientController::class, 'search']);
    
    // Collections
    Route::apiResource('collections', CollectionController::class);
    Route::get('/collections/search/{term}', [CollectionController::class, 'search']);
    
    // Items
    Route::apiResource('items', ItemController::class);
    Route::post('/items/{item}/upload-images', [ItemController::class, 'uploadImages']);
    Route::delete('/items/{item}/images/{type}', [ItemController::class, 'deleteImage']);
    Route::get('/collections/{collection}/items', [ItemController::class, 'getByCollection']);
    
    // Notes
    Route::apiResource('notes', NoteController::class);
    Route::post('/notes/{note}/items', [NoteController::class, 'addItem']);
    Route::put('/notes/{note}/items/{item}', [NoteController::class, 'updateItem']);
    Route::delete('/notes/{note}/items/{item}', [NoteController::class, 'removeItem']);
    Route::get('/notes/search/{term}', [NoteController::class, 'search']);
    
    // Financial
    Route::get('/financial/dashboard', [FinancialController::class, 'dashboard']);
    Route::post('/notes/{note}/payments', [FinancialController::class, 'addPayment']);
    Route::put('/notes/{note}/payments/{payment}', [FinancialController::class, 'updatePayment']);
    Route::get('/financial/reports', [FinancialController::class, 'reports']);
    
});