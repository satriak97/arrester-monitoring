<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $authUser = auth()->user();

        $users = User::with('adminCabang')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('username', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%')
                    ->orWhere('cabang', 'like', '%' . $search . '%');
                });
            })
            ->when($authUser->role === 'admin_cabang', function ($query) use ($authUser) {
                $query->where('admin_cabang_id', $authUser->id);
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $adminCabangs = \App\Models\User::where('role', 'admin_cabang')->orderBy('name')->get();

        return view('users.create', compact('adminCabangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'required|in:admin_cabang,user',
            'cabang' => 'required|string|max:255',
            'admin_cabang_id' => 'nullable|exists:users,id',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'cabang' => $validated['cabang'],
            'admin_cabang_id' => $validated['role'] === 'user'
                ? ($validated['admin_cabang_id'] ?? null)
                : null,
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $adminCabangs = User::where('role', 'admin_cabang')->orderBy('name')->get();

        return view('users.edit', compact('user', 'adminCabangs'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin_master') {
            return redirect()->route('users.index')->with('success', 'Admin Master tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin_cabang,user',
            'cabang' => 'required|string|max:255',
            'admin_cabang_id' => 'nullable|exists:users,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'role' => $validated['role'],
            'cabang' => $validated['cabang'],
            'admin_cabang_id' => $validated['role'] === 'user'
                ? ($validated['admin_cabang_id'] ?? null)
                : null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin_master') {
            return redirect()->route('users.index')->with('success', 'Admin Master tidak dapat dihapus.');
        }

        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')->with('success', 'Akun yang sedang login tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}