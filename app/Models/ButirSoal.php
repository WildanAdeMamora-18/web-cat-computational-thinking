<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ButirSoal extends Model
{
    protected $table = 'butir_soal';
    protected $guarded = ['id'];
    protected $hidden = ['kunci_jawaban', 'ukuran_b'];

    protected $casts = [
        'ukuran_b'   => 'float',
        'status_fit' => 'boolean',
    ];
}
