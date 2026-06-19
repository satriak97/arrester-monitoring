<?php

namespace App\Http\Controllers;

use App\Models\Gi;
use App\Models\Penyulang;
use Illuminate\Http\Request;

class GiPenyulangController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->search;

        $gis = \App\Models\Gi::orderBy('nama_gi')->get();

        $penyulangs = \App\Models\Penyulang::with('gi')
            ->when(in_array($user->role, ['admin_cabang', 'user']), function ($q) use ($user) {
                $q->where('cabang', $user->cabang);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('id', 'like', '%' . $search . '%')
                        ->orWhere('nama_penyulang', 'like', '%' . $search . '%')
                        ->orWhere('cabang', 'like', '%' . $search . '%')
                        ->orWhereHas('gi', function ($giQuery) use ($search) {
                            $giQuery->where('nama_gi', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('nama_penyulang')
            ->paginate(10)
            ->withQueryString();

        return view('gi-penyulang.index', compact('gis', 'penyulangs', 'search'));
    }

    public function storeGi(Request $request)
    {
        abort_if(auth()->user()->role === 'user', 403);

        $request->validate([
            'nama_gi' => 'required|string|max:255|unique:gis,nama_gi',
        ]);

        Gi::create([
            'nama_gi' => strtoupper($request->nama_gi),
        ]);

        return back()->with('success', 'GI berhasil ditambahkan.');
    }

    public function updateGi(Request $request, $id)
    {
        abort_if(auth()->user()->role === 'user', 403);

        $gi = Gi::findOrFail($id);

        $request->validate([
            'nama_gi' => 'required|string|max:255|unique:gis,nama_gi,' . $gi->id,
        ]);

        $gi->update([
            'nama_gi' => strtoupper($request->nama_gi),
        ]);

        return back()->with('success', 'GI berhasil diupdate.');
    }

    public function destroyGi($id)
    {
        abort_if(auth()->user()->role === 'user', 403);

        $gi = Gi::withCount('penyulangs')->findOrFail($id);

        if ($gi->penyulangs_count > 0) {
            return back()->withErrors([
                'gi' => 'GI tidak bisa dihapus karena masih memiliki penyulang.'
            ]);
        }

        $gi->delete();

        return back()->with('success', 'GI berhasil dihapus.');
    }

    public function storePenyulang(Request $request)
    {
        $user = auth()->user();

        abort_if($user->role === 'user', 403);

        $request->validate([
            'gi_id' => 'required|exists:gis,id',
            'nama_penyulang' => 'required|string|max:255',
            'cabang' => $user->role === 'admin_master'
                ? 'required|string|max:255'
                : 'nullable',
        ]);

        $cabang = $user->role === 'admin_master'
            ? strtoupper($request->cabang)
            : $user->cabang;

        Penyulang::create([
            'gi_id' => $request->gi_id,
            'nama_penyulang' => strtoupper($request->nama_penyulang),
            'cabang' => $cabang,
        ]);

        return back()->with('success', 'Penyulang berhasil ditambahkan.');
    }

    public function updatePenyulang(Request $request, $id)
    {
        $user = auth()->user();

        abort_if($user->role === 'user', 403);

        $penyulang = Penyulang::when($user->role === 'admin_cabang', function ($q) use ($user) {
                $q->where('cabang', $user->cabang);
            })
            ->findOrFail($id);

        $request->validate([
            'gi_id' => 'required|exists:gis,id',
            'nama_penyulang' => 'required|string|max:255',
            'cabang' => $user->role === 'admin_master'
                ? 'required|string|max:255'
                : 'nullable',
        ]);

        $penyulang->update([
            'gi_id' => $request->gi_id,
            'nama_penyulang' => strtoupper($request->nama_penyulang),
            'cabang' => $user->role === 'admin_master'
                ? strtoupper($request->cabang)
                : $user->cabang,
        ]);

        return back()->with('success', 'Penyulang berhasil diupdate.');
    }

    public function destroyPenyulang($id)
    {
        $user = auth()->user();

        abort_if($user->role === 'user', 403);

        $penyulang = Penyulang::withCount('arresters')
            ->when($user->role === 'admin_cabang', function ($q) use ($user) {
                $q->where('cabang', $user->cabang);
            })
            ->findOrFail($id);

        if ($penyulang->arresters_count > 0) {
            return back()->withErrors([
                'penyulang' => 'Penyulang tidak bisa dihapus karena masih digunakan oleh data arrester.'
            ]);
        }

        $penyulang->delete();

        return back()->with('success', 'Penyulang berhasil dihapus.');
    }
}