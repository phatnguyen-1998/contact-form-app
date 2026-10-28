<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ContactController::class, 'index']);
Route::post('/contacts/confirm', [ContactController::class, 'store']);
Route::post('/contacts', [ContactController::class, 'thanks_store']);
Route::get('/thanks', [ContactController::class, 'thanks']);
