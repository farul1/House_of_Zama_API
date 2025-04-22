<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotographyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function() {
    return response()->json(['message' => 'PhotographyService aktif']);
});


Route::get('/photographies', [PhotographyController::class, 'index']);
Route::post('/photographies', [PhotographyController::class, 'store']);
Route::get('/photographies/{id}', [PhotographyController::class, 'show']);
