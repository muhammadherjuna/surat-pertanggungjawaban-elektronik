<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\JenisSpjController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:4,5'])->prefix('master')->name('master.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('bidangs', BidangController::class);
    Route::resource('rekenings', RekeningController::class);
    Route::resource('jenis-spjs', JenisSpjController::class);
});

Route::middleware(['auth', 'role:0'])->prefix('operator')->name('operator.')->group(function () {
    Route::resource('spj', \App\Http\Controllers\Operator\SpjController::class);
    Route::post('spj/{spj}/dokumen', [\App\Http\Controllers\Operator\SpjController::class, 'storeDokumen'])->name('spj.dokumen.store');
    Route::delete('spj/{spj}/dokumen/{dokumen}', [\App\Http\Controllers\Operator\SpjController::class, 'destroyDokumen'])->name('spj.dokumen.destroy');
    Route::post('spj/{spj}/submit', [\App\Http\Controllers\Operator\SpjController::class, 'submit'])->name('spj.submit');
});

Route::middleware(['auth', 'role:1,2,3'])->prefix('approval')->name('approval.')->group(function () {
    Route::get('spj', [\App\Http\Controllers\Approval\SpjController::class, 'index'])->name('spj.index');
    Route::get('spj/{spj}', [\App\Http\Controllers\Approval\SpjController::class, 'show'])->name('spj.show');
    Route::post('spj/{spj}/approve', [\App\Http\Controllers\Approval\SpjController::class, 'approve'])->name('spj.approve');
    Route::post('spj/{spj}/reject', [\App\Http\Controllers\Approval\SpjController::class, 'reject'])->name('spj.reject');
});

Route::middleware(['auth', 'role:4'])->prefix('bendahara')->name('bendahara.')->group(function () {
    Route::get('spj', [\App\Http\Controllers\Bendahara\SpjController::class, 'index'])->name('spj.index');
    Route::get('spj/{spj}', [\App\Http\Controllers\Bendahara\SpjController::class, 'show'])->name('spj.show');
    Route::post('spj/{spj}/verify', [\App\Http\Controllers\Bendahara\SpjController::class, 'verify'])->name('spj.verify');
    Route::get('spj/{spj}/print', [\App\Http\Controllers\Bendahara\SpjController::class, 'printPdf'])->name('spj.print');
});

// Public Route
Route::get('/public/spj/{uuid}', [\App\Http\Controllers\PublicSpjController::class, 'verify'])->name('public.spj.verify');
