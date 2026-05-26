<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::resource('books', BookController::class);
Route::resource('readers', ReaderController::class)->except(['show']);
Route::resource('borrowings', BorrowingController::class)->except(['edit', 'update', 'show']);
Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');

Route::get('/system-check', [App\Http\Controllers\SystemCheckController::class, 'index'])->name('system.check');
