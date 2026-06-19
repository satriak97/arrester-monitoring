<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measurement;

class MeasurementHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $user = auth()->user();

        $measurements = Measurement::with('arrester.penyulang.gi')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('arrester_id', 'like', '%' . $search . '%')
                        ->orWhereHas('arrester', function ($arresterQuery) use ($search) {
                            $arresterQuery->where('lokasi', 'like', '%' . $search . '%')
                                ->orWhere('cabang', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('arrester.penyulang', function ($penyulangQuery) use ($search) {
                            $penyulangQuery->where('id', 'like', '%' . $search . '%')
                                ->orWhere('nama_penyulang', 'like', '%' . $search . '%')
                                ->orWhere('cabang', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('arrester.penyulang.gi', function ($giQuery) use ($search) {
                            $giQuery->where('nama_gi', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                if ($status === 'NORMAL') {
                    $query->where('leak_current', '<', 1);
                } elseif ($status === 'WARNING') {
                    $query->whereBetween('leak_current', [1, 10]);
                } elseif ($status === 'FAULT') {
                    $query->where('leak_current', '>', 10);
                }
            })
            ->when($user && in_array($user->role, ['admin_cabang', 'user']), function ($query) use ($user) {
                $query->whereHas('arrester', function ($q) use ($user) {
                    $q->where('cabang', $user->cabang);
                });
            })
            ->orderByDesc('measured_at')
            ->paginate(10)
            ->withQueryString();

        return view('measurement.index', compact('measurements', 'search', 'status'));
    }

    public function showByArrester($arrester_id)
    {
        $user = auth()->user();

        $measurements = Measurement::with('arrester.penyulang.gi')
            ->where('arrester_id', $arrester_id)
            ->when($user && in_array($user->role, ['admin_cabang', 'user']), function ($query) use ($user) {
                $query->whereHas('arrester', function ($q) use ($user) {
                    $q->where('cabang', $user->cabang);
                });
            })
            ->orderByDesc('measured_at')
            ->paginate(10)
            ->withQueryString();

        return view('measurement.by-arrester', compact('measurements', 'arrester_id'));
    }
}