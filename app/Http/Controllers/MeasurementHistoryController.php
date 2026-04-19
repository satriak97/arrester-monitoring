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

        $measurements = \App\Models\Measurement::with('arrester')
            ->when($search, function ($query) use ($search) {
                $query->where('arrester_id', 'like', '%' . $search . '%');
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
            ->when($user->role === 'admin_cabang', function ($query) use ($user) {
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

        $measurements = \App\Models\Measurement::with('arrester')
            ->where('arrester_id', $arrester_id)
            ->when($user->role === 'admin_cabang', function ($query) use ($user) {
                $query->whereHas('arrester', function ($q) use ($user) {
                    $q->where('cabang', $user->cabang);
                });
            })
            ->orderByDesc('measured_at')
            ->paginate(10);

        return view('measurement.by-arrester', compact('measurements', 'arrester_id'));
    }
}