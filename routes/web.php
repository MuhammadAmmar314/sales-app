<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
