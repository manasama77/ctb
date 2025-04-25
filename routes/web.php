<?php

use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\DataAbsensiController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Volt::route('dashboard', 'dashboard')->name('dashboard');

    Route::get('data-absensi', [DataAbsensiController::class, 'index'])->name('data-absensi');
    Route::get('data-absensi/rekap', [DataAbsensiController::class, 'rekap'])->name('data-absensi.rekap');

    Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan');
    Route::get('karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
    Route::post('karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
    Route::get('karyawan/edit/{user}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
    Route::put('karyawan/update/{user}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::get('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password'])->name('karyawan.reset-password');
    Route::patch('karyawan/reset-password/{user}', [KaryawanController::class, 'reset_password_process'])->name('karyawan.reset-password-proses');
    Route::delete('karyawan/destroy/{user}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
