<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::resource('books', BookController::class);
Route::resource('readers', ReaderController::class);
Route::resource('borrowings', BorrowingController::class)->except(['edit', 'update', 'show']);
Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');

Route::resource('authors', AuthorController::class);
Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('branches', BranchController::class)->except(['show']);
Route::resource('reservations', ReservationController::class)->only(['index', 'create', 'store', 'destroy']);
Route::patch('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
Route::patch('reservations/{reservation}/fulfill', [ReservationController::class, 'fulfill'])->name('reservations.fulfill');
Route::resource('fines', FineController::class)->only(['index']);
Route::patch('fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/system-check', [App\Http\Controllers\SystemCheckController::class, 'index'])->name('system.check');

Route::get('/export/{resource}/csv', [ExportController::class, 'csv'])->name('export.csv');

Route::get('/mobile', [MobileController::class, 'home'])->name('mobile.home');

Route::get('/api/books', [ApiController::class, 'books']);
Route::get('/api/books/{book}', [ApiController::class, 'bookShow']);
Route::post('/api/books', [ApiController::class, 'bookStore']);
Route::get('/api/readers', [ApiController::class, 'readers']);
Route::get('/api/readers/{reader}', [ApiController::class, 'readerShow']);
Route::get('/api/borrowings', [ApiController::class, 'borrowings']);
Route::get('/api/borrowings/active', [ApiController::class, 'activeBorrowings']);
Route::post('/api/borrowings', [ApiController::class, 'borrowingStore']);
Route::patch('/api/borrowings/{borrowing}/return', [ApiController::class, 'borrowingReturn']);
Route::get('/api/fines', [ApiController::class, 'fines']);
Route::get('/api/reservations', [ApiController::class, 'reservations']);
Route::get('/api/stats', [ApiController::class, 'stats']);
