<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyulang extends Model
{
    protected $fillable = [
        'gi_id',
        'nama_penyulang',
        'cabang',
    ];

    public function gi()
    {
        return $this->belongsTo(Gi::class);
    }

    public function arresters()
    {
        return $this->hasMany(\App\Models\Arrester::class);
    }
}