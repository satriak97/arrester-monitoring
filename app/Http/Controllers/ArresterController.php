<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrester;
use App\Models\Penyulang;

class ArresterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $authUser = auth()->user();

        $arresters = Arrester::with(['penyulang.gi'])

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('kode_arrester', 'like', '%' . $search . '%')
                      ->orWhere('lokasi', 'like', '%' . $search . '%')
                      ->orWhere('cabang', 'like', '%' . $search . '%')

                      ->orWhereHas('penyulang', function ($p) use ($search) {
                          $p->where('nama_penyulang', 'like', '%' . $search . '%');
                      })

                      ->orWhereHas('penyulang.gi', function ($g) use ($search) {
                          $g->where('nama_gi', 'like', '%' . $search . '%');
                      });

                });
            })

            ->when(
                in_array($authUser->role, ['admin_cabang', 'user']),
                function ($query) use ($authUser) {
                    $query->where('cabang', $authUser->cabang);
                }
            )

            ->orderBy('kode_arrester', 'asc')
            ->paginate(10)
            ->withQueryString();

        $penyulangs = Penyulang::with('gi')

            ->when(
                in_array($authUser->role, ['admin_cabang', 'user']),
                function ($query) use ($authUser) {
                    $query->where('cabang', $authUser->cabang);
                }
            )

            ->orderBy('nama_penyulang')
            ->get();

        return view('arrester.index', compact(
            'arresters',
            'search',
            'penyulangs'
        ));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        if ($authUser->role === 'user') {
            abort(403);
        }

        $validated = $request->validate([
            'nomor_arrester' => 'required|numeric|min:1|max:9999',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'penyulang_id' => 'required|exists:penyulangs,id',
        ]);

        $penyulang = Penyulang::findOrFail($validated['penyulang_id']);

        if ($authUser->role === 'admin_master') {
            $cabang = $penyulang->cabang;
        } else {
            $cabang = $authUser->cabang;
        }

        $prefixCabang = $this->getKodeCabang($cabang);

        $kodePenyulang = str_pad($penyulang->id, 2, '0', STR_PAD_LEFT);

        $nomorBelakang = str_pad(
            $validated['nomor_arrester'],
            4,
            '0',
            STR_PAD_LEFT
        );

        $kodeArrester = $prefixCabang . $kodePenyulang . $nomorBelakang;

        // Cek unik
        $exists = Arrester::where(
            'kode_arrester',
            $kodeArrester
        )->exists();

        if ($exists) {

            return back()
                ->withErrors([
                    'nomor_arrester' => 'Kode arrester sudah digunakan!'
                ])
                ->withInput();
        }

        Arrester::create([
            'kode_arrester' => $kodeArrester,
            'lokasi' => $validated['lokasi'],
            'cabang' => $cabang,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'penyulang_id' => $validated['penyulang_id'],
        ]);

        return redirect()
            ->route('arrester.index')
            ->with('success', 'Data arrester berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();

        if ($authUser->role === 'user') {
            abort(403);
        }

        $arrester = Arrester::when(
                $authUser->role === 'admin_cabang',
                function ($query) use ($authUser) {
                    $query->where('cabang', $authUser->cabang);
                }
            )
            ->findOrFail($id);

        $validated = $request->validate([
            'nomor_arrester' => 'required|numeric|min:1|max:9999',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'penyulang_id' => 'required|exists:penyulangs,id',
        ]);

        $penyulang = Penyulang::findOrFail($validated['penyulang_id']);

        if ($authUser->role === 'admin_master') {
            $cabang = $penyulang->cabang;
        } else {
            $cabang = $authUser->cabang;
        }

        $prefixCabang = $this->getKodeCabang($cabang);

        $kodePenyulang = str_pad($penyulang->id, 2, '0', STR_PAD_LEFT);

        $nomorBelakang = str_pad(
            $validated['nomor_arrester'],
            4,
            '0',
            STR_PAD_LEFT
        );

        $kodeArrester = $prefixCabang . $kodePenyulang . $nomorBelakang;

        $exists = Arrester::where(
                'kode_arrester',
                $kodeArrester
            )
            ->where('id', '!=', $arrester->id)
            ->exists();

        if ($exists) {

            return back()
                ->withErrors([
                    'nomor_arrester' => 'Kode arrester sudah digunakan!'
                ])
                ->withInput();
        }

        $arrester->update([
            'kode_arrester' => $kodeArrester,
            'lokasi' => $validated['lokasi'],
            'cabang' => $cabang,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'penyulang_id' => $validated['penyulang_id'],
        ]);

        return redirect()
            ->route('arrester.index')
            ->with('success', 'Data arrester berhasil diupdate.');
    }

    public function destroy($id)
    {
        $authUser = auth()->user();

        if ($authUser->role === 'user') {
            abort(403);
        }

        $arrester = Arrester::when(
                $authUser->role === 'admin_cabang',
                function ($query) use ($authUser) {
                    $query->where('cabang', $authUser->cabang);
                }
            )
            ->findOrFail($id);

        $arrester->delete();

        return redirect()
            ->route('arrester.index')
            ->with('success', 'Data arrester berhasil dihapus.');
    }

    private function getKodeCabang($cabang)
    {
        return match (strtoupper(trim($cabang))) {

            'KOTA' => '1',
            'MANDE' => '2',
            'CIPANAS' => '3',
            'SUKANAGARA' => '4',
            'TANGGEUNG' => '5',

            default => '9',
        };
    }
}