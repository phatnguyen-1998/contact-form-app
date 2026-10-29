<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index']);
Route::post('/contacts/confirm', [ContactController::class, 'store']);
Route::post('/contacts', [ContactController::class, 'thanks_store']);
Route::get('/thanks', [ContactController::class, 'thanks']);

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/contacts/export', [ContactController::class, 'export']);
    Route::post('/admin/tags', [TagController::class, 'store']);
    Route::get('/admin/tags/{tag}/edit', [TagController::class, 'edit']);
    Route::put('/admin/tags/{tag}', [TagController::class, 'update']);
});
