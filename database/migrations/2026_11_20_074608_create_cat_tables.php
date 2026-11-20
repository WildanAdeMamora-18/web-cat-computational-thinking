<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sesi_tes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sesi', 100);
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->timestamps();
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peserta', 30)->unique();
            $table->string('nama', 100);
            $table->string('kelas', 30)->default('X MPK 1');
            $table->timestamps();
        });

        Schema::create('butir_soal', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_butir')->unique();
            $table->enum('indikator', [
                'Dekomposisi',
                'Abstraksi',
                'Pengenalan Pola',
                'Perancangan Algoritma',
                'Analisis dan Evaluasi Solusi'
            ]);
            $table->text('teks_soal');
            $table->string('gambar')->nullable();
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->text('opsi_e');
            $table->enum('kunci_jawaban', ['A', 'B', 'C', 'D', 'E']);
            $table->decimal('ukuran_b', 8, 4)->default(0.0000);
            $table->boolean('status_fit')->default(true);
            $table->timestamps();
        });

        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('sesi_id')->constrained('sesi_tes')->cascadeOnDelete();
            $table->foreignId('butir_aktif_id')->nullable()->constrained('butir_soal')->nullOnDelete();
            $table->decimal('theta_akhir', 8, 4)->default(0.0000);
            $table->decimal('sem_akhir', 8, 4)->nullable();
            $table->integer('jumlah_butir')->default(0);
            $table->enum('status_tes', ['sedang_mengerjakan', 'selesai'])->default('sedang_mengerjakan');
            $table->string('alasan_berhenti', 50)->nullable();
            $table->timestamp('waktu_mulai')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'sesi_id']);
        });

        Schema::create('respons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_tes_id')->constrained('hasil_tes')->cascadeOnDelete();
            $table->foreignId('butir_soal_id')->constrained('butir_soal')->restrictOnDelete();
            $table->integer('urutan');
            $table->enum('jawaban_dipilih', ['A', 'B', 'C', 'D', 'E']);
            $table->boolean('skor');
            $table->decimal('theta_setelah', 8, 4);
            $table->decimal('sem_setelah', 8, 4)->nullable();
            $table->timestamp('waktu_jawab')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respons');
        Schema::dropIfExists('hasil_tes');
        Schema::dropIfExists('butir_soal');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('sesi_tes');
    }
};
