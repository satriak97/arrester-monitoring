<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\Arrester;
use Carbon\Carbon;

class MeasurementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'arrester_id'  => 'required',
            'leak_current' => 'required|numeric',
        ]);

        $arrester = Arrester::where('kode_arrester', $validated['arrester_id'])->first();

        if (!$arrester) {
            return response()->json([
                'message' => 'Kode arrester tidak terdaftar',
            ], 404);
        }

        $leakCurrent = (float) $validated['leak_current'];

        if ($leakCurrent < 1) {
            $status = 'NORMAL';
        } elseif ($leakCurrent <= 10) {
            $status = 'WARNING';
        } else {
            $status = 'FAULT';
        }

        $measurement = Measurement::create([
            'arrester_id'  => $validated['arrester_id'],
            'leak_current' => $leakCurrent,
            'status'       => $status,
            'measured_at'  => now(),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data'    => $measurement,
        ], 201);
    }

    public function latest()
    {
        $data = Measurement::with('arrester.penyulang.gi')
            ->orderByDesc('measured_at')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'arrester_id' => $item->arrester_id,
                    'gi' => optional(optional(optional($item->arrester)->penyulang)->gi)->nama_gi,
                    'id_penyulang' => optional(optional($item->arrester)->penyulang)->id,
                    'penyulang' => optional(optional($item->arrester)->penyulang)->nama_penyulang,
                    'lokasi' => optional($item->arrester)->lokasi,
                    'latitude' => optional($item->arrester)->latitude,
                    'longitude' => optional($item->arrester)->longitude,
                    'leak_current' => (float) $item->leak_current,
                    'status' => $item->status,
                    'measured_at' => \Carbon\Carbon::parse($item->measured_at)->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($data);
    }

    private function getStatusFromLeakCurrent($leakCurrent)
    {
        $leakCurrent = (float) $leakCurrent;

        if ($leakCurrent < 1) {
            return 'NORMAL';
        }

        if ($leakCurrent <= 10) {
            return 'WARNING';
        }

        return 'FAULT';
    }
}