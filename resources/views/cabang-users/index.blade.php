<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title" style="margin-bottom: 20px;">Kelola User Cabang</h2>

        <div class="panel">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
                <a href="{{ route('cabang-users.create') }}" class="btn">Tambah User Cabang</a>

                <form action="{{ route('cabang-users.index') }}" method="GET" style="display:flex; gap:10px;">
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

            <div style="margin-bottom: 14px; font-weight: 600;">
                Cabang: {{ auth()->user()->cabang }}
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->username }}</td>
                                <td>{{ $u->email ?? '-' }}</td>
                                <td>{{ ucfirst($u->role) }}</td>
                                <td>{{ $u->cabang }}</td>
                                <td>
                                    <a href="{{ route('cabang-users.edit', $u->id) }}" class="btn">Edit</a>

                                    <form action="{{ route('cabang-users.destroy', $u->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Belum ada user cabang.</td>
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