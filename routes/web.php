<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeputadoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/deputado/detalhado/{id}', [DeputadoController::class, 'getDeputadoDetalhado'])->name('getDeputadoDetalhado');

    Route::post('/sincronizar/deputados', [DeputadoController::class, 'sincronizarDeputados'])->name('deputados.sincronizar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
