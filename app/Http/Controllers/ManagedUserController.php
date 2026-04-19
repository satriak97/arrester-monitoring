<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ManagedUserController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $search = $request->search;

        $users = User::with('adminCabang')
            ->where('admin_cabang_id', $authUser->id)
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

        return view('managed-users.index', compact('users', 'search'));
    }
}