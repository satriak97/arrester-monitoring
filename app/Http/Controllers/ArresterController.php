<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arrester;

class ArresterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $authUser = auth()->user();

        $arresters = Arrester::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_arrester', 'like', '%' . $search . '%')
                      ->orWhere('lokasi', 'like', '%' . $search . '%')
                      ->orWhere('cabang', 'like', '%' . $search . '%');
                });
            })
            ->when($authUser->role === 'admin_cabang', function ($query) use ($authUser) {
                $query->where('cabang', $authUser->cabang);
            })
            ->orderBy('kode_arrester', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('arrester.index', compact('arresters', 'search'));
    }

    public function create()
    {
        return view('arrester.create');
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $validated = $request->validate([
            'nomor_arrester' => 'required|numeric|min:1|max:9999',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Tentukan cabang
        if ($authUser->role === 'admin_master') {
            $request->validate([
                'cabang' => 'required|string|max:255',
            ]);
            $cabang = $request->input('cabang');
        } else {
            $cabang = $authUser->cabang;
        }

        $prefix = $this->getKodeCabang($cabang);

        // 5 digit total = 1 digit prefix + 4 digit nomor
        $nomorBelakang = str_pad($validated['nomor_arrester'], 4, '0', STR_PAD_LEFT);
        $kodeArrester = $prefix . $nomorBelakang;

        // Cek unik
        if (Arrester::where('kode_arrester', $kodeArrester)->exists()) {
            return back()
                ->withErrors(['nomor_arrester' => 'Kode arrester sudah digunakan!'])
                ->withInput();
        }

        Arrester::create([
            'kode_arrester' => $kodeArrester,
            'lokasi' => $validated['lokasi'],
            'cabang' => $cabang,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return redirect()->route('arrester.index')->with('success', 'Data arrester berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $authUser = auth()->user();

        $arrester = Arrester::when($authUser->role === 'admin_cabang', function ($query) use ($authUser) {
                $query->where('cabang', $authUser->cabang);
            })
            ->findOrFail($id);

        return view('arrester.edit', compact('arrester'));
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();

        $arrester = Arrester::when($authUser->role === 'admin_cabang', function ($query) use ($authUser) {
                $query->where('cabang', $authUser->cabang);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'nomor_arrester' => 'required|numeric|min:1|max:9999',
            'lokasi' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Tentukan cabang
        if ($authUser->role === 'admin_master') {
            $request->validate([
                'cabang' => 'required|string|max:255',
            ]);
            $cabang = $request->input('cabang');
        } else {
            $cabang = $authUser->cabang;
        }

        $prefix = $this->getKodeCabang($cabang);

        // 5 digit total = 1 digit prefix + 4 digit nomor
        $nomorBelakang = str_pad($validated['nomor_arrester'], 4, '0', STR_PAD_LEFT);
        $kodeArrester = $prefix . $nomorBelakang;

        $exists = Arrester::where('kode_arrester', $kodeArrester)
            ->where('id', '!=', $arrester->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['nomor_arrester' => 'Kode arrester sudah digunakan!'])
                ->withInput();
        }

        $arrester->update([
            'kode_arrester' => $kodeArrester,
            'lokasi' => $validated['lokasi'],
            'cabang' => $cabang,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return redirect()->route('arrester.index')->with('success', 'Data arrester berhasil diupdate.');
    }

    public function destroy($id)
    {
        $authUser = auth()->user();

        $arrester = Arrester::when($authUser->role === 'admin_cabang', function ($query) use ($authUser) {
                $query->where('cabang', $authUser->cabang);
            })
            ->findOrFail($id);

        $arrester->delete();

        return redirect()->route('arrester.index')->with('success', 'Data arrester berhasil dihapus.');
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