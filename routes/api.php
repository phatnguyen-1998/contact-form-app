<?php

use App\Http\Controllers\Api\V1\ContactController;
use Illuminate\Support\Facades\Route;

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
$contactNotFound = fn () => response()->json(['error' => 'お問い合わせが見つかりませんでした。'], 404);
Route::prefix('v1')->group(function () use ($contactNotFound) {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->missing($contactNotFound);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->missing($contactNotFound);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->missing($contactNotFound);
});
