<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilDiagnosaController;
use App\Http\Controllers\KelolaDiagnosaController;
use App\Http\Controllers\KelolaDokterController;
use App\Http\Controllers\KelolaJadwalPraktikController;
use App\Http\Controllers\KelolaObatController;
use App\Http\Controllers\KelolaUsersController;
use App\Http\Controllers\ListPendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PendaftaranPasienController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route yang memerlukan auth dan verified
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pendaftaran Pasien
    Route::prefix('pendaftaran-pasien')->group(function () {
        Route::get('/', [PendaftaranPasienController::class, 'index'])->name('pendaftaran-pasien.index');
        Route::post('/', [PendaftaranPasienController::class, 'store'])->name('pendaftaran-pasien.store');
        Route::put('/{id}', [PendaftaranPasienController::class, 'update'])->name('pendaftaran-pasien.update');
        Route::delete('/{id}', [PendaftaranPasienController::class, 'destroy'])->name('pendaftaran-pasien.destroy');
    });

    // Hasil Diagnosa
    Route::prefix('hasil-diagnosa')->group(function () {
        Route::get('/', [HasilDiagnosaController::class, 'index'])->name('hasil-diagnosa.index');
        Route::post('/', [HasilDiagnosaController::class, 'store'])->name('hasil-diagnosa.store');
        Route::put('/{id}', [HasilDiagnosaController::class, 'update'])->name('hasil-diagnosa.update');
        Route::delete('/{id}', [HasilDiagnosaController::class, 'destroy'])->name('hasil-diagnosa.destroy');
        Route::post('/get-no-rm', [HasilDiagnosaController::class, 'getNoRM'])->name('hasil-diagnosa.get-no-rm');
    });

    // Pembayaran
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // Report
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/medical-records', [ReportController::class, 'medicalRecords'])->name('report.medical-records');

    // Kelola Dokter
    Route::prefix('kelola-dokter')->group(function () {
        Route::get('/', [KelolaDokterController::class, 'index'])->name('kelola-dokter.index');
        Route::post('/', [KelolaDokterController::class, 'store'])->name('kelola-dokter.store');
        Route::put('/{id}', [KelolaDokterController::class, 'update'])->name('kelola-dokter.update');
        Route::delete('/{id}', [KelolaDokterController::class, 'destroy'])->name('kelola-dokter.destroy');
    });

    // Kelola Obat
    Route::prefix('kelola-obat')->group(function () {
        Route::get('/', [KelolaObatController::class, 'index'])->name('kelola-obat.index');
        Route::post('/', [KelolaObatController::class, 'store'])->name('kelola-obat.store');
        Route::put('/{id}', [KelolaObatController::class, 'update'])->name('kelola-obat.update');
        Route::delete('/{id}', [KelolaObatController::class, 'destroy'])->name('kelola-obat.destroy');
    });

    // Kelola Diagnosa
    Route::prefix('kelola-diagnosa')->group(function () {
        Route::get('/', [KelolaDiagnosaController::class, 'index'])->name('kelola-diagnosa.index');
        Route::post('/', [KelolaDiagnosaController::class, 'store'])->name('kelola-diagnosa.store');
        Route::put('/{id}', [KelolaDiagnosaController::class, 'update'])->name('kelola-diagnosa.update');
        Route::delete('/{id}', [KelolaDiagnosaController::class, 'destroy'])->name('kelola-diagnosa.destroy');
    });

    // Kelola Users
    Route::prefix('kelola-users')->group(function () {
        Route::get('/', [KelolaUsersController::class, 'index'])->name('kelola-users.index');
        Route::post('/', [KelolaUsersController::class, 'store'])->name('kelola-users.store');
        Route::put('/{id}', [KelolaUsersController::class, 'update'])->name('kelola-users.update');
        Route::delete('/{id}', [KelolaUsersController::class, 'destroy'])->name('kelola-users.destroy');
    });
});

require __DIR__ . '/auth.php';
