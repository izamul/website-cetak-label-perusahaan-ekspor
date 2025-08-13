<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LabelAssetController;

Route::get('/', fn()=>redirect()->route('labels.index'));
Route::get('/dashboard', function () {
    return redirect()->route('labels.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function(){
    Route::resource('labels', LabelController::class);
    Route::post('labels/{label}/assets/{slot}', [LabelAssetController::class,'store'])->name('labels.assets.store');
    Route::delete('labels/{label}/assets/{slot}', [LabelAssetController::class,'destroy'])->name('labels.assets.destroy');
    Route::get('labels/{label}/print', [LabelController::class,'print'])->name('labels.print');
    Route::get('labels/{label}/pdf', [LabelController::class,'pdf'])->name('labels.pdf'); // optional
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
