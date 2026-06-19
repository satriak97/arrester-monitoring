<x-app-layout>
    <div class="user-page">
        <h2 class="page-title">Kelola User Cabang</h2>

        <div class="panel">

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="top-bar">
                <button type="button" class="btn" onclick="openModal('createUserCabangModal')">
                    Tambah User Cabang
                </button>

                <form action="{{ route('cabang-users.index') }}" method="GET" class="search-bar">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama / username..."
                        class="search-input"
                    >

                    <button type="submit" class="btn">
                        Cari
                    </button>
                </form>
            </div>

            <div class="cabang-info">
                Cabang:
                <span class="cabang-badge">
                    {{ auth()->user()->cabang }}
                </span>
            </div>

            <div class="table-wrapper">
                <table class="user-table">
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
                                <td>
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $u->name }}</strong>
                                </td>

                                <td>
                                    <span class="username-badge">
                                        {{ $u->username }}
                                    </span>
                                </td>

                                <td>
                                    {{ $u->email ?? '-' }}
                                </td>

                                <td>
                                    <span class="role-badge {{ $u->role }}">
                                        {{ strtoupper($u->role) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="cabang-badge">
                                        {{ $u->cabang }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('cabang-users.edit', $u->id) }}"
                                            class="btn btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('cabang-users.destroy', $u->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">👤</div>

                                        <div class="empty-title">
                                            Belum ada user cabang
                                        </div>

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

            @if($users->count() > 0)
                <div class="pagination-wrapper">
                    {{ $users->links('vendor.pagination.pln-pagination') }}
                </div>
            @endif

        </div>
    </div>

    <div id="createUserCabangModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Tambah User Cabang</h3>
                <button type="button" onclick="closeModal('createUserCabangModal')">×</button>
            </div>

            <form action="{{ route('cabang-users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>

                <div class="form-group">
                    <label>Cabang</label>
                    <input type="text" value="{{ auth()->user()->cabang }}" disabled>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserCabangModal')">
                        Batal
                    </button>
                    <button type="submit" class="btn">
                        Simpan
                    </button>
                </div>
            </form>
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

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            border: 1px solid #bbf7d0;
            font-weight: 700;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 18px;
            border: 1px solid #fecaca;
            font-weight: 700;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #fff;
            font-size: 14px;
            min-width: 260px;
            min-height: 46px;
        }

        .cabang-info {
            margin-bottom: 18px;
            font-weight: 700;
            color: #111827;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .user-table th {
            background: #0891b2;
            color: white;
            padding: 12px;
            font-weight: 800;
            text-align: center;
            border: 1px solid #d1d5db;
        }

        .user-table td {
            padding: 11px 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .user-table tbody tr:hover {
            background: #f1f5f9;
        }

        .username-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
            font-size: 12px;
        }

        .cabang-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-weight: 800;
            font-size: 12px;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
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

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 7px 12px;
            font-size: 13px;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
        }

        .empty-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .empty-text {
            color: #6b7280;
            font-size: 14px;
        }

        .pagination-wrapper {
            margin-top: 20px;
        }

        @media (max-width: 768px) {

            .user-page {
                padding: 14px;
            }

            .page-title {
                font-size: 28px;
                margin-bottom: 16px;
            }

            .top-bar,
            .search-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input,
            .btn {
                width: 100%;
                max-width: 100%;
            }

            .user-table th,
            .user-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 10px 8px;
            }
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 100%;
            max-width: 620px;
            background: #fff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #111827;
        }

        .modal-header button {
            border: none;
            background: #f3f4f6;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 22px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .btn-secondary {
            background: #6b7280;
        }
    </style>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('show');
            }
        });
    </script>
</x-app-layout>