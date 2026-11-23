<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->integer('jumlah_keluar_tab')->default(0)->after('alasan_berhenti');
            $table->timestamp('terakhir_keluar_tab')->nullable()->after('jumlah_keluar_tab');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_tes', function (Blueprint $table) {
            $table->dropColumn(['jumlah_keluar_tab', 'terakhir_keluar_tab']);
        });
    }
};
