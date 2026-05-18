<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DokumenController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/document');
});

Route::get('/dashboard', function () {
    return redirect('/document');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::resource('document', DokumenController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/document/{id}/typing', [DokumenController::class, 'typing']);

    Route::post('/document/{id}/cursor', [DokumenController::class, 'cursor']);

});

require __DIR__.'/auth.php';