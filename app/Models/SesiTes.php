<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiTes extends Model
{
    protected $table = 'sesi_tes';
    protected $guarded = ['id'];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function hasilTes()
    {
        return $this->hasMany(HasilTes::class, 'sesi_id');
    }
}
