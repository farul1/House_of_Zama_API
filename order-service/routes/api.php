<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function() {
    return response()->json(['message' => 'OrderService aktif']);
});

Route::resource('orders', OrderController::class);
