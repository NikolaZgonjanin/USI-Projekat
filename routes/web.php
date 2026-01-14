<?php

use App\Http\Controllers\FirmwareVersionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin panel - upravljanje korisnicima
    Route::middleware('can:viewAny,App\Models\User')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Projekti - svi autentifikovani korisnici mogu da vide projekte
    Route::resource('projects', ProjectController::class);

    // Firmver verzije
    Route::resource('firmware-versions', FirmwareVersionController::class);
    // UC2: Inženjer dodaje novu firmver verziju za konkretan projekat (GET forma)
    Route::get('projects/{project}/firmware/create', [FirmwareVersionController::class, 'create'])
        ->name('projects.firmware.create');
    // UC3: Download firmvera
    Route::get('firmware-versions/{firmwareVersion}/download', [FirmwareVersionController::class, 'download'])
        ->name('firmware.download');

    // Prijave grešaka
    Route::resource('support-requests', SupportRequestController::class);
    // UC1: Prijava problema za određenu verziju firmvera (GET forma)
    Route::get('firmware-versions/{firmwareVersion}/support/create', [SupportRequestController::class, 'create'])
        ->name('firmware.support.create');
});

require __DIR__.'/auth.php';
