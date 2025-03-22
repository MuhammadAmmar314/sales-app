<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/transactions', [TransactionController::class, 'index'])->name('api.transactions.index');
Route::post('/transactions', [TransactionController::class, 'store'])->name('api.transactions.store');
Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('api.transactions.show');
Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('api.transactions.update');
Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('api.transactions.destroy');
Route::get('/compare', [TransactionController::class, 'compare'])->name('api.transactions.compare');

Route::get('/items', [ItemController::class, 'index'])->name('api.items.index');
Route::post('/items', [ItemController::class, 'store'])->name('api.items.store');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('api.items.show');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('api.items.update');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('api.items.destroy');
Route::get('/select-items', [ItemController::class, 'selectItems'])->name('api.items.select');
