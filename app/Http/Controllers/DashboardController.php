<?php

namespace App\Http\Controllers;

use App\Models\Arrester;
use App\Models\Measurement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $arresterQuery = Arrester::with('latestMeasurement');

        if ($user->role === 'admin_cabang') {
            $arresterQuery->where('cabang', $user->cabang);
        }

        $arresters = $arresterQuery->get();

        // Card grid: hitung dari status TERAKHIR tiap arrester
        $normal = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'NORMAL';
        })->count();

        $warning = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'WARNING';
        })->count();

        $fault = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'FAULT';
        })->count();

        // Tabel: tetap ambil 20 measurement terbaru
        $measurementsQuery = Measurement::with('arrester');

        if ($user->role === 'admin_cabang') {
            $measurementsQuery->whereHas('arrester', function ($q) use ($user) {
                $q->where('cabang', $user->cabang);
            });
        }

        $measurements = $measurementsQuery
            ->orderByDesc('measured_at')
            ->limit(20)
            ->get();

        // Map: pakai latestMeasurement, NO_DATA hanya jika belum pernah ada measurement
        $mapData = $arresters
            ->filter(function ($arrester) {
                return !is_null($arrester->latitude) && !is_null($arrester->longitude);
            })
            ->map(function ($arrester) {
                $latestMeasurement = $arrester->latestMeasurement;

                return [
                    'kode_arrester' => $arrester->kode_arrester,
                    'lokasi' => $arrester->lokasi,
                    'latitude' => (float) $arrester->latitude,
                    'longitude' => (float) $arrester->longitude,
                    'leak_current' => $latestMeasurement?->leak_current,
                    'status' => $latestMeasurement
                        ? strtoupper(trim($latestMeasurement->status))
                        : 'NO_DATA',
                    'measured_at' => $latestMeasurement?->measured_at
                        ? Carbon::parse($latestMeasurement->measured_at)->format('d-m-Y H:i:s')
                        : null,
                ];
            })
            ->values();

        return view('dashboard', compact(
            'measurements',
            'normal',
            'warning',
            'fault',
            'mapData'
        ));
    }
}