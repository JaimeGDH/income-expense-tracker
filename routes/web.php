<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/transactions/filter/{transaction_type_id}', function ($transaction_type_id) {
    return view('transactions')->with('transaction_type_id', $transaction_type_id);
})->middleware(['auth', 'verified'])->name('transactions');

Route::get('/summary', [TransactionController::class, 'summary'])->name('summary');

Route::get('/expenses', function () {
    return view('expenses');
})->middleware(['auth', 'verified'])->name('expenses');

Route::get('/resume', function () {
    return view('resume');
})->middleware(['auth', 'verified'])->name('resume');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
