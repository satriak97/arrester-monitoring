<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gi extends Model
{
    protected $fillable = [
        'nama_gi'
    ];

    public function penyulangs()
    {
        return $this->hasMany(Penyulang::class);
    }
}