<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Arrester;

class Measurement extends Model
{
    protected $fillable = [
        'arrester_id',
        'leak_current',
        'measured_at'
    ];

    // STATUS KONDISI ARRESTER
    public function getStatusAttribute()
    {
        if ($this->leak_current < 1) {
            return 'NORMAL';
        } elseif ($this->leak_current <= 10) {
            return 'WARNING';
        } else {
            return 'FAULT';
        }
    }

    // RELASI KE TABEL ARRESTERS
    public function arrester()
    {
        return $this->belongsTo(Arrester::class, 'arrester_id', 'kode_arrester');
    }
}