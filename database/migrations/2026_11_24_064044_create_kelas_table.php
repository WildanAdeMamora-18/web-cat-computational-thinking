<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30)->unique();
            $table->timestamps();
        });

        $kelasSiswa = DB::table('siswa')->select('kelas')->distinct()->pluck('kelas')->toArray();
        $kelasAwal = array_unique(array_merge(
            ['X MPK 1', 'X MPK 2', 'X MPK 3', 'X MPK 4', 'X MPK 5'],
            $kelasSiswa
        ));
        sort($kelasAwal);

        foreach ($kelasAwal as $nama) {
            if (trim($nama) !== '') {
                DB::table('kelas')->insertOrIgnore([
                    'nama'       => strtoupper(trim($nama)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
