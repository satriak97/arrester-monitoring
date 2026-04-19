<x-app-layout>
    <div class="user-page">
        <h2 class="page-title">Kelola User Cabang</h2>

        <div class="panel">

            {{-- TOP BAR --}}
            <div class="top-bar">
                <a href="{{ url('/users/create') }}" class="btn">+ Tambah User</a>

                <form action="{{ url('/users') }}" method="GET" class="search-bar">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari username / cabang..."
                        class="search-input"
                    >
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td>{{ $u->username }}</td>

                                <td>
                                    <span class="role-badge {{ $u->role }}">
                                        {{ strtoupper($u->role) }}
                                    </span>
                                </td>

                                <td>{{ $u->cabang ?? '-' }}</td>

                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ url('/users/'.$u->id.'/edit') }}" class="btn btn-sm">
                                            Edit
                                        </a>

                                        @if($u->role !== 'admin_master')
                                            <form action="{{ url('/users/'.$u->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus user ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">👤</div>
                                        <div class="empty-title">Belum ada user</div>
                                        <div class="empty-text">
                                            Tambahkan user cabang untuk mulai menggunakan sistem monitoring.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($users->count() > 0)
                <div class="simple-pagination">
                    {{ $users->onEachSide(1)->links('vendor.pagination.simple-minimal') }}
                </div>
            @endif

        </div>
    </div>

    <style>
        .user-page {
            padding: 20px;
        }

        .page-title {
            margin: 0 0 22px 0;
            font-size: 34px;
            font-weight: 800;
            color: #111827;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            min-width: 260px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 14px;
            overflow: hidden;
            background: white;
        }

        .user-table th,
        .user-table td {
            border: 1px solid #e5e7eb;
            padding: 14px;
            text-align: center;
        }

        .user-table th {
            background: #f9fafb;
            font-weight: 700;
        }

        .user-table tr:hover {
            background: #f3f4f6;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* ROLE BADGE */
        .role-badge {
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .role-badge.admin_master {
            background: #fee2e2;
            color: #b91c1c;
        }

        .role-badge.admin_cabang {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .role-badge.user {
            background: #dcfce7;
            color: #166534;
        }

        /* EMPTY STATE */
        .empty-state {
            padding: 40px;
            text-align: center;
        }

        .empty-icon {
            font-size: 36px;
        }

        .empty-title {
            font-weight: 700;
            font-size: 20px;
            margin-top: 10px;
        }

        .empty-text {
            color: #6b7280;
            font-size: 14px;
        }

        /* PAGINATION FIX */
        .simple-pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .simple-pagination nav {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .simple-pagination nav > div:first-child {
            display: none;
        }

        .simple-pagination nav > div:last-child {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .simple-pagination ul {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .simple-pagination li {
            margin: 0;
        }

        .simple-pagination a,
        .simple-pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #111827;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-shadow: none;
        }

        .simple-pagination a:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
        }

        .simple-pagination .active span,
        .simple-pagination span[aria-current="page"] {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
        }

        .simple-pagination .disabled span {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .simple-pagination svg {
            width: 16px;
            height: 16px;
        }

        @media (max-width: 768px) {
            .simple-pagination a,
            .simple-pagination span {
                min-width: 34px;
                height: 34px;
                font-size: 13px;
                padding: 0 10px;
            }

            .simple-pagination ul {
                gap: 6px;
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</x-app-layout>