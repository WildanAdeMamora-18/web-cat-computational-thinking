<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    protected $table = 'hasil_tes';
    protected $guarded = ['id'];

    protected $casts = [
        'theta_akhir'   => 'float',
        'sem_akhir'     => 'float',
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function sesi()
    {
        return $this->belongsTo(SesiTes::class, 'sesi_id');
    }

    public function butirAktif()
    {
        return $this->belongsTo(ButirSoal::class, 'butir_aktif_id');
    }

    public function respons()
    {
        return $this->hasMany(Respons::class, 'hasil_tes_id')->orderBy('urutan');
    }
}
