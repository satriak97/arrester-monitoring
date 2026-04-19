<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title" style="margin-bottom: 20px;">User Cabang</h2>

        <div class="panel">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
                <div style="font-weight:600;">
                    Cabang: {{ auth()->user()->cabang }}
                </div>

                <form action="{{ route('managed-users.index') }}" method="GET" style="display:flex; gap:10px;">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama / username..."
                        style="padding:10px; border:1px solid #ccc; border-radius:8px; min-width:220px;"
                    >
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Admin Cabang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->username }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $u->role)) }}</td>
                                <td>{{ $u->cabang ?? '-' }}</td>
                                <td>{{ optional($u->adminCabang)->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Belum ada user cabang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper" style="margin-top:20px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>