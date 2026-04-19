<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CabangUserController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $search = $request->search;

        $users = User::where('admin_cabang_id', $authUser->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('username', 'like', '%' . $search . '%')
                      ->orWhere('cabang', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('cabang-users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('cabang-users.create');
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'cabang' => $authUser->cabang,
            'admin_cabang_id' => $authUser->id,
        ]);

        return redirect()->route('cabang-users.index')->with('success', 'User cabang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $authUser = auth()->user();

        $user = User::where('admin_cabang_id', $authUser->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('cabang-users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();

        $user = User::where('admin_cabang_id', $authUser->id)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'cabang' => $authUser->cabang,
            'admin_cabang_id' => $authUser->id,
            'role' => 'user',
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('cabang-users.index')->with('success', 'User cabang berhasil diupdate.');
    }

    public function destroy($id)
    {
        $authUser = auth()->user();

        $user = User::where('admin_cabang_id', $authUser->id)
            ->where('id', $id)
            ->firstOrFail();

        $user->delete();

        return redirect()->route('cabang-users.index')->with('success', 'User cabang berhasil dihapus.');
    }
}