<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Measurement;

class MeasurementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'arrester_id'  => 'required',
            'leak_current' => 'required|numeric',
        ]);

        $measurement = Measurement::create([
            'arrester_id'  => $validated['arrester_id'],
            'leak_current' => $validated['leak_current'],
            'measured_at'  => now(),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data'    => $measurement,
        ], 201);
    }

    public function latest()
    {
        $data = Measurement::with('arrester')
            ->orderByDesc('measured_at')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'arrester_id'  => $item->arrester_id,
                    'lokasi'       => optional($item->arrester)->lokasi,
                    'latitude'     => optional($item->arrester)->latitude,
                    'longitude'    => optional($item->arrester)->longitude,
                    'leak_current' => $item->leak_current,
                    'status'       => $item->status,
                    'measured_at'  => \Carbon\Carbon::parse($item->measured_at)
                                        ->format('d-m-Y H:i:s'),
                ];
            });

        return response()->json($data);
    }
}