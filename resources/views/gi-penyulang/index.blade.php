<x-app-layout>
    <div class="gi-page">
        <div class="panel">
            <h2 class="page-title">GI & Penyulang</h2>

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(auth()->user()->role !== 'user')
                <div class="form-grid">
                    <form action="{{ route('gi.store') }}" method="POST" class="form-card">
                        @csrf

                        <h3>Tambah GI</h3>

                        <div class="form-group">
                            <label>Nama GI</label>
                            <input type="text" name="nama_gi" placeholder="Contoh: CIANJUR" required>
                        </div>

                        <button type="submit" class="btn">Tambah GI</button>
                    </form>

                    <form action="{{ route('penyulang.store') }}" method="POST" class="form-card">
                        @csrf

                        <h3>Tambah Penyulang</h3>

                        <div class="form-group">
                            <label>Pilih GI</label>
                            <select name="gi_id" required>
                                <option value="">-- Pilih GI --</option>
                                @foreach($gis as $gi)
                                    <option value="{{ $gi->id }}">{{ $gi->nama_gi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nama Penyulang</label>
                            <input type="text" name="nama_penyulang" placeholder="Contoh: TAKOKAK" required>
                        </div>

                        @if(auth()->user()->role === 'admin_master')
                            <div class="form-group">
                                <label>Cabang</label>
                                <input type="text" name="cabang" placeholder="Contoh: KOTA" required>
                            </div>
                        @else
                            <div class="form-group">
                                <label>Cabang</label>
                                <input type="text" value="{{ auth()->user()->cabang }}" disabled>
                            </div>
                        @endif

                        <button type="submit" class="btn">Tambah Penyulang</button>
                    </form>
                </div>
            @endif

            <div class="top-bar">
                <form action="{{ route('gi-penyulang.index') }}" method="GET" class="search-bar">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari ID / GI / penyulang / cabang..."
                        class="search-input"
                    >

                    <button type="submit" class="btn">Cari</button>

                    @if(request('search'))
                        <a href="{{ route('gi-penyulang.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="gi-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Prnyulang</th>
                            <th>Nama Penyulang</th>
                            <th>Nama Gardu Induk</th>
                            <th>Cabang</th>

                            @if(auth()->user()->role !== 'user')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($penyulangs as $p)
                            <tr>
                                <td>{{ ($penyulangs->currentPage() - 1) * $penyulangs->perPage() + $loop->iteration }}</td>

                                <td>
                                    <span class="id-penyulang-badge">
                                        {{ $p->id }}
                                    </span>
                                </td>

                                <td>
                                    <strong>{{ $p->nama_penyulang }}</strong>
                                </td>

                                <td>{{ optional($p->gi)->nama_gi ?? '-' }}</td>

                                <td>
                                    <span class="cabang-badge">{{ $p->cabang ?? '-' }}</span>
                                </td>

                                @if(auth()->user()->role !== 'user')
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm" onclick="openModal('editPenyulang{{ $p->id }}')">
                                                Ubah
                                            </button>

                                            <form action="{{ route('penyulang.destroy', $p->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus penyulang ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            @if(auth()->user()->role !== 'user')
                                <div id="editPenyulang{{ $p->id }}" class="modal-overlay">
                                    <div class="modal-box">
                                        <div class="modal-header">
                                            <h3>Edit Penyulang</h3>
                                            <button type="button" onclick="closeModal('editPenyulang{{ $p->id }}')">×</button>
                                        </div>

                                        <form action="{{ route('penyulang.update', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <label>GI</label>
                                                <select name="gi_id" required>
                                                    @foreach($gis as $gi)
                                                        <option value="{{ $gi->id }}" {{ $p->gi_id == $gi->id ? 'selected' : '' }}>
                                                            {{ $gi->nama_gi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Nama Penyulang</label>
                                                <input type="text" name="nama_penyulang" value="{{ $p->nama_penyulang }}" required>
                                            </div>

                                            @if(auth()->user()->role === 'admin_master')
                                                <div class="form-group">
                                                    <label>Cabang</label>
                                                    <input type="text" name="cabang" value="{{ $p->cabang }}" required>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label>Cabang</label>
                                                    <input type="text" value="{{ $p->cabang }}" disabled>
                                                </div>
                                            @endif

                                            <div class="modal-actions">
                                                <button type="button" class="btn btn-secondary" onclick="closeModal('editPenyulang{{ $p->id }}')">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role !== 'user' ? 6 : 5 }}">
                                    <div class="empty-state">
                                        Belum ada data penyulang.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($penyulangs->count() > 0)
                <div class="pagination-wrapper">
                    {{ $penyulangs->links('vendor.pagination.pln-pagination') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .gi-page {
            padding: 20px;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 26px;
        }

        .form-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px;
        }

        .form-card h3 {
            margin: 0 0 16px 0;
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .form-group select {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: white;
        }

        .gi-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .gi-table th {
            background: #0891b2;
            color: white;
            padding: 12px;
            font-weight: 800;
            text-align: center;
            border: 1px solid #d1d5db;
        }

        .gi-table td {
            padding: 11px 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .gi-table tbody tr:hover {
            background: #f1f5f9;
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

        .btn-secondary {
            background: #6b7280;
        }

        .empty-state {
            padding: 24px;
            color: #6b7280;
            font-weight: 700;
            text-align: center;
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
            max-width: 520px;
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
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
            .gi-page {
                padding: 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .gi-table th,
            .gi-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 10px;
            }

            .modal-actions {
                flex-direction: column;
            }

            .id-penyulang-badge{
                display:inline-block;
                padding:6px 12px;
                border-radius:999px;
                background:#e0e7ff;
                color:#3730a3;
                font-weight:700;
                font-size:13px;
                min-width:50px;
            }
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
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
            min-width: 300px;
            min-height: 46px;
        }

        .btn-secondary {
            background: #6b7280;
        }

        @media (max-width: 768px) {
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
        }
    </style>

    @if(auth()->user()->role !== 'user')
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
    @endif
</x-app-layout>