<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrester extends Model
{
    protected $fillable = [
        'kode_arrester',
        'lokasi',
        'cabang',
        'latitude',
        'longitude',
    ];

    public function measurements()
    {
        return $this->hasMany(\App\Models\Measurement::class, 'arrester_id', 'kode_arrester');
    }

    public function latestMeasurement()
    {
        return $this->hasOne(\App\Models\Measurement::class, 'arrester_id', 'kode_arrester')
            ->latestOfMany('measured_at');
    }
}