<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'TopSol API',
        'version' => '2.0.0',
        'status' => 'running',
        'documentation' => '/api/documentation'
    ]);
});

Route::fallback(function () {
    return response()->json([
        'error' => 'Not Found',
        'message' => 'The requested endpoint does not exist.'
    ], 404);
});