<?php

namespace App\Http\Controllers;

use App\Models\Arrester;
use App\Models\Measurement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $restrictedRoles = ['admin_cabang', 'user'];

        $arresterQuery = Arrester::with('latestMeasurement');

        if (in_array($user->role, $restrictedRoles)) {
            $arresterQuery->where('cabang', $user->cabang);
        }

        $arresters = $arresterQuery->get();

        $normal = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'NORMAL';
        })->count();

        $warning = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'WARNING';
        })->count();

        $fault = $arresters->filter(function ($arrester) {
            return optional($arrester->latestMeasurement)->status === 'FAULT';
        })->count();

        $measurementsQuery = Measurement::with('arrester');

        if (in_array($user->role, $restrictedRoles)) {
            $measurementsQuery->whereHas('arrester', function ($q) use ($user) {
                $q->where('cabang', $user->cabang);
            });
        }

        $measurements = $measurementsQuery
            ->orderByDesc('measured_at')
            ->limit(20)
            ->get();

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