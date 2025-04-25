<?php

use App\Http\Controllers\DataAbsensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Volt::route('dashboard', 'dashboard')->name('dashboard');

    Route::get('data-absensi', [DataAbsensiController::class, 'index'])->name('data-absensi');
    Route::get('data-absensi/rekap', [DataAbsensiController::class, 'rekap'])->name('data-absensi.rekap')->middleware(AdminMiddleware::class);
    Route::get('data-absensi/in', [DataAbsensiController::class, 'in'])->name('data-absensi.in');
    Route::post('data-absensi/in', [DataAbsensiController::class, 'in_store'])->name('data-absensi.in.store');
    Route::get('data-absensi/out', [DataAbsensiController::class, 'out'])->name('data-absensi.out');
    Route::post('data-absensi/out', [DataAbsensiController::class, 'out_store'])->name('data-absensi.out.store');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan');
        Route::get('karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('karyawan/edit/{user}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('karyawan/update/{user}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password'])->name('karyawan.reset-password');
        Route::patch('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password_process'])->name('karyawan.reset-password-proses');
        Route::delete('karyawan/destroy/{user}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    });

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
