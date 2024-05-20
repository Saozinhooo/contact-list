<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/person');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('person', [PersonController::class, 'index'])->name('person.index');
Route::get('/create', [PersonController::class, 'create'])->name('person.create');
Route::post('/store', [PersonController::class, 'store'])->name('person.store');
Route::get('/{person}/edit', [PersonController::class, 'edit'])->name('person.edit');
Route::post('/{person}/update', [PersonController::class, 'update'])->name('person.update');
Route::get('/{person}/destroy', [PersonController::class, 'destroy'])->name('person.destroy');

Route::get('/search/results', [PersonController::class, 'search'])->name('search');
require __DIR__.'/auth.php';
