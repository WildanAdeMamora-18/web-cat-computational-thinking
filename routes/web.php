<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UjianController;
use Illuminate\Support\Facades\Route;

// === RUTE SISWA ===
Route::get('/', [UjianController::class, 'showLogin'])->name('siswa.login');
Route::post('/login', [UjianController::class, 'login'])->name('siswa.login.post');

Route::middleware('siswa.auth')->prefix('ujian')->name('ujian.')->group(function () {
    Route::get('/petunjuk', [UjianController::class, 'petunjuk'])->name('petunjuk');
    Route::get('/tes', [UjianController::class, 'tampilSoal'])->name('tes');
    Route::post('/submit', [UjianController::class, 'submitJawaban'])->name('submit');
    Route::get('/submit', fn() => redirect()->route('ujian.tes')); // Pengaman jika browser auto-reload
    Route::post('/catat-keluar-tab', [UjianController::class, 'catatKeluarTab'])->name('keluar_tab');
    Route::get('/selesai', [UjianController::class, 'selesai'])->name('selesai');
});

// === RUTE ADMIN ===
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/', fn() => redirect()->route('admin.siswa'))->name('dashboard');

        // 1. Data Siswa
        Route::get('/siswa', [AdminController::class, 'siswa'])->name('siswa');
        Route::get('/siswa/next-kode', [AdminController::class, 'getNextKode'])->name('siswa.next_kode');
        Route::post('/siswa', [AdminController::class, 'simpanSiswa'])->name('siswa.simpan');
        Route::put('/siswa/{siswa}', [AdminController::class, 'updateSiswa'])->name('siswa.update');
        Route::post('/siswa/{siswa}/reset-tes', [AdminController::class, 'resetTesSiswa'])->name('siswa.reset');
        Route::delete('/siswa/{siswa}', [AdminController::class, 'hapusSiswa'])->name('siswa.hapus');
        Route::delete('/siswa/hapus-kelas', [AdminController::class, 'hapusKelas'])->name('siswa.hapus_kelas');
        Route::post('/siswa/impor', [AdminController::class, 'imporSiswaCsv'])->name('siswa.impor');
        Route::post('/kelas', [AdminController::class, 'tambahKelasDropdown'])->name('kelas.simpan');
        Route::delete('/kelas', [AdminController::class, 'hapusKelasDropdown'])->name('kelas.hapus');

        // 2. Bank Soal
        Route::get('/soal', [AdminController::class, 'soal'])->name('soal');
        Route::post('/soal', [AdminController::class, 'simpanSoal'])->name('soal.simpan');
        Route::put('/soal/{soal}', [AdminController::class, 'updateKalibrasiSoal'])->name('soal.update');
        Route::post('/soal/impor-kalibrasi', [AdminController::class, 'imporKalibrasiCsv'])->name('soal.impor');

        // 3. Sesi Tes
        Route::get('/sesi', [AdminController::class, 'sesi'])->name('sesi');
        Route::post('/sesi', [AdminController::class, 'simpanSesi'])->name('sesi.simpan');
        Route::post('/sesi/{sesi}/toggle', [AdminController::class, 'toggleSesi'])->name('sesi.toggle');
        Route::delete('/sesi/{sesi}', [AdminController::class, 'hapusSesi'])->name('sesi.hapus');

        // 4. Hasil Tes & Ekspor CSV
        Route::get('/hasil', [AdminController::class, 'hasil'])->name('hasil');
        Route::delete('/hasil/{hasil}', [AdminController::class, 'hapusHasil'])->name('hasil.hapus');
        Route::get('/export/ringkasan', [AdminController::class, 'exportRingkasanCsv'])->name('export.ringkasan');
        Route::get('/export/respons', [AdminController::class, 'exportResponsCsv'])->name('export.respons');
    });
});
