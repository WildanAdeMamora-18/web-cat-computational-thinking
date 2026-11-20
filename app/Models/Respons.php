<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respons extends Model
{
    protected $table = 'respons';
    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'skor'          => 'integer',
        'theta_setelah' => 'float',
        'sem_setelah'   => 'float',
        'waktu_jawab'   => 'datetime',
    ];

    public function hasilTes()
    {
        return $this->belongsTo(HasilTes::class, 'hasil_tes_id');
    }

    public function butirSoal()
    {
        return $this->belongsTo(ButirSoal::class, 'butir_soal_id');
    }
}
