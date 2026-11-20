<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = ['id'];

    public function hasilTes()
    {
        return $this->hasMany(HasilTes::class, 'siswa_id');
    }
}
