<x-app-layout>
    <div class="user-page">
        <h2 class="page-title">Kelola User</h2>

        <div class="panel">
            <div class="top-bar">
                <button type="button" class="btn" onclick="openModal('createUserModal')">
                    Tambah User
                </button>

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

            <div class="table-wrapper">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Cabang</th>
                            <th>Admin Cabang</th>
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
                                <td>{{ optional($u->adminCabang)->name ?? '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm" onclick="openModal('editUserModal{{ $u->id }}')">
                                            Edit
                                        </button>

                                        @if($u->role !== 'admin_master')
                                            <form action="{{ url('/users/'.$u->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT USER --}}
                            <div id="editUserModal{{ $u->id }}" class="modal-overlay">
                                <div class="modal-box">
                                    <div class="modal-header">
                                        <h3>Edit User</h3>
                                        <button type="button" onclick="closeModal('editUserModal{{ $u->id }}')">×</button>
                                    </div>

                                    <form action="{{ url('/users/'.$u->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" name="username" value="{{ $u->username }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="name" value="{{ $u->name }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $u->email }}">
                                        </div>

                                        @if($u->role !== 'admin_master')
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select name="role" class="role-select" data-target="editCabangManual{{ $u->id }}" data-admin="editAdminCabang{{ $u->id }}" required>
                                                    <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User</option>
                                                    <option value="admin_cabang" {{ $u->role === 'admin_cabang' ? 'selected' : '' }}>Admin Cabang</option>
                                                </select>
                                            </div>

                                            <div class="form-group cabang-manual-field" id="editCabangManual{{ $u->id }}">
                                                <label>Cabang</label>
                                                <input type="text" name="cabang" value="{{ $u->cabang }}">
                                                <small>Diisi hanya untuk role Admin Cabang.</small>
                                            </div>

                                            <div class="form-group admin-cabang-field" id="editAdminCabang{{ $u->id }}">
                                                <label>Admin Cabang</label>
                                                <select name="admin_cabang_id">
                                                    <option value="">-- Pilih Admin Cabang --</option>
                                                    @isset($adminCabangs)
                                                        @foreach($adminCabangs as $adminCabang)
                                                            <option value="{{ $adminCabang->id }}"
                                                                {{ $u->admin_cabang_id == $adminCabang->id ? 'selected' : '' }}>
                                                                {{ $adminCabang->name }} - {{ $adminCabang->cabang }}
                                                            </option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                                <small>Untuk role User, cabang otomatis mengikuti Admin Cabang.</small>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label>Role</label>
                                                <input type="text" value="ADMIN MASTER" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Cabang</label>
                                                <input type="text" value="{{ $u->cabang }}" disabled>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <input type="password" name="password_confirmation" placeholder="Kosongkan jika tidak diganti">
                                        </div>

                                        <div class="modal-actions">
                                            <button type="button" class="btn btn-secondary" onclick="closeModal('editUserModal{{ $u->id }}')">Batal</button>
                                            <button type="submit" class="btn">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6">
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

            @if($users->count() > 0)
                <div class="pagination-wrapper" style="margin-top:20px;">
                    {{ $users->links('vendor.pagination.pln-pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL TAMBAH USER --}}
    <div id="createUserModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Tambah User</h3>
                <button type="button" onclick="closeModal('createUserModal')">×</button>
            </div>

            <form action="{{ url('/users') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="createRoleSelect" required>
                        <option value="user">User</option>
                        <option value="admin_cabang">Admin Cabang</option>
                    </select>
                </div>

                <div class="form-group cabang-manual-field" id="createCabangManual">
                    <label>Cabang</label>
                    <input type="text" name="cabang">
                    <small>Diisi hanya untuk role Admin Cabang.</small>
                </div>

                <div class="form-group admin-cabang-field" id="createAdminCabang">
                    <label>Admin Cabang</label>
                    <select name="admin_cabang_id">
                        <option value="">-- Pilih Admin Cabang --</option>
                        @isset($adminCabangs)
                            @foreach($adminCabangs as $adminCabang)
                                <option value="{{ $adminCabang->id }}">
                                    {{ $adminCabang->name }} - {{ $adminCabang->cabang }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <small>Untuk role User, cabang otomatis mengikuti Admin Cabang.</small>
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal('createUserModal')">Batal</button>
                    <button type="submit" class="btn">Simpan</button>
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

        .btn-secondary {
            background: #6b7280;
        }

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

        small {
            display: block;
            margin-top: 6px;
            color: #6b7280;
            font-size: 12px;
        }

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

        @media (max-width: 768px) {
            .user-page {
                padding: 14px;
            }

            .page-title {
                font-size: 28px;
                margin-bottom: 16px;
            }

            .top-bar {
                flex-direction: column;
            }

            .search-bar {
                width: 100%;
                flex-direction: column;
            }

            .search-input,
            .btn {
                width: 100%;
            }

            .user-table th,
            .user-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 8px;
            }

            .modal-actions {
                flex-direction: column;
            }
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

        function toggleUserFields(roleSelect) {
            const role = roleSelect.value;
            const cabangTarget = roleSelect.dataset.target;
            const adminTarget = roleSelect.dataset.admin;

            const cabangField = cabangTarget ? document.getElementById(cabangTarget) : document.getElementById('createCabangManual');
            const adminField = adminTarget ? document.getElementById(adminTarget) : document.getElementById('createAdminCabang');

            if (!cabangField || !adminField) return;

            const cabangInput = cabangField.querySelector('input[name="cabang"]');
            const adminSelect = adminField.querySelector('select[name="admin_cabang_id"]');

            if (role === 'admin_cabang') {
                cabangField.style.display = 'block';
                adminField.style.display = 'none';

                if (cabangInput) cabangInput.required = true;
                if (adminSelect) {
                    adminSelect.required = false;
                    adminSelect.value = '';
                }
            } else {
                cabangField.style.display = 'none';
                adminField.style.display = 'block';

                if (cabangInput) {
                    cabangInput.required = false;
                    cabangInput.value = '';
                }
                if (adminSelect) adminSelect.required = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const createRole = document.getElementById('createRoleSelect');
            if (createRole) {
                toggleUserFields(createRole);
                createRole.addEventListener('change', function () {
                    toggleUserFields(this);
                });
            }

            document.querySelectorAll('.role-select').forEach(function(select) {
                toggleUserFields(select);
                select.addEventListener('change', function () {
                    toggleUserFields(this);
                });
            });
        });
    </script>
</x-app-layout>