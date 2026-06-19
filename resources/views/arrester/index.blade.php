<x-app-layout>
    @php
        $canManageArrester = auth()->user()->role !== 'user';
        $emptyColspan = $canManageArrester ? 9 : 8;
    @endphp

    <div class="arrester-page">
        <h2 class="page-title">Data Arrester</h2>

        <div class="panel">
            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="top-bar">
                @if($canManageArrester)
                    <button type="button" class="btn" onclick="openModal('createModal')">
                        Tambah Arrester
                    </button>
                @else
                    <div class="info-readonly">
                        Gunakan Kode Arrester untuk input ID pada alat ukur.
                    </div>
                @endif

                <form action="{{ url('/arrester') }}" method="GET" class="search-bar">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode / GI / penyulang / lokasi..."
                        class="search-input"
                    >

                    <button type="submit" class="btn">Cari</button>

                    @if(request('search'))
                        <a href="{{ url('/arrester') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-wrapper">
                <table class="arrester-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Arrester</th>
                            <th>Cabang</th>
                            <th>GI</th>
                            <th>ID Penyulang</th>
                            <th>Penyulang</th>
                            <th>Lokasi</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            @if($canManageArrester)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($arresters as $a)
                            <tr>
                                <td>{{ ($arresters->currentPage() - 1) * $arresters->perPage() + $loop->iteration }}</td>

                                <td>
                                    <span class="kode-badge">{{ $a->kode_arrester }}</span>
                                </td>

                                <td>
                                    <span class="cabang-badge">{{ $a->cabang ?? '-' }}</span>
                                </td>

                                <td>
                                    <span class="gi-badge">
                                        {{ optional(optional($a->penyulang)->gi)->nama_gi ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="kode-badge">
                                        {{ optional($a->penyulang)->id ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="penyulang-badge">
                                        {{ optional($a->penyulang)->nama_penyulang ?? '-' }}
                                    </span>
                                </td>

                                <td>{{ $a->lokasi }}</td>
                                <td>{{ $a->latitude ?? '-' }}</td>
                                <td>{{ $a->longitude ?? '-' }}</td>

                                @if($canManageArrester)
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-sm" onclick="openModal('editModal{{ $a->id }}')">
                                                Edit
                                            </button>

                                            <form action="{{ url('/arrester/'.$a->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            @if($canManageArrester)
                                <div id="editModal{{ $a->id }}" class="modal-overlay">
                                    <div class="modal-box">
                                        <div class="modal-header">
                                            <h3>Edit Arrester</h3>
                                            <button type="button" onclick="closeModal('editModal{{ $a->id }}')">×</button>
                                        </div>

                                        @php
                                            $nomorBelakang = substr($a->kode_arrester, 1);
                                        @endphp

                                        <form action="{{ url('/arrester/'.$a->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="form-group">
                                                <label>Kode Arrester Saat Ini</label>
                                                <input type="text" value="{{ $a->kode_arrester }}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Nomor Arrester</label>
                                                <input type="number" name="nomor_arrester" value="{{ $nomorBelakang }}" min="1" max="999999" required>
                                                <small>Nomor belakang diisi manual. Prefix mengikuti cabang.</small>
                                            </div>

                                            <div class="form-group">
                                                <label>Cabang</label>
                                                <input type="text" value="{{ $a->cabang }}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>GI / Penyulang</label>
                                                <select name="penyulang_id" class="select-penyulang" required>
                                                    <option value="">-- Pilih Penyulang --</option>
                                                    @foreach($penyulangs as $p)
                                                        <option value="{{ $p->id }}" {{ $a->penyulang_id == $p->id ? 'selected' : '' }}>
                                                            {{ optional($p->gi)->nama_gi ?? '-' }} - {{ $p->nama_penyulang }} ({{ $p->cabang }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Lokasi</label>
                                                <input type="text" name="lokasi" value="{{ $a->lokasi }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Latitude</label>
                                                <input type="text" name="latitude" value="{{ $a->latitude }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Longitude</label>
                                                <input type="text" name="longitude" value="{{ $a->longitude }}">
                                            </div>

                                            <div class="modal-actions">
                                                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal{{ $a->id }}')">
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
                                <td colspan="{{ $emptyColspan }}">
                                    <div class="empty-state">
                                        <div class="empty-icon">📍</div>
                                        <div class="empty-title">Belum ada data arrester</div>
                                        <div class="empty-text">
                                            Data arrester yang telah ditambahkan akan tampil di halaman ini.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($arresters->count() > 0)
                <div class="pagination-wrapper">
                    {{ $arresters->links('vendor.pagination.pln-pagination') }}
                </div>
            @endif
        </div>
    </div>

    @if($canManageArrester)
        <div id="createModal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <h3>Tambah Arrester</h3>
                    <button type="button" onclick="closeModal('createModal')">×</button>
                </div>

                <form action="{{ url('/arrester') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nomor Arrester</label>
                        <input type="number" name="nomor_arrester" min="1" max="9999" required>
                        <small>Nomor belakang diisi manual. Prefix mengikuti cabang penyulang.</small>
                    </div>

                    @if(auth()->user()->role === 'admin_master')
                        <div class="form-group">
                            <label>Cabang</label>
                            <input type="text" value="Otomatis mengikuti cabang penyulang" disabled>
                        </div>
                    @else
                        <div class="form-group">
                            <label>Cabang</label>
                            <input type="text" value="{{ auth()->user()->cabang }}" disabled>
                        </div>
                    @endif

                    <div class="form-group">
                        <label>GI / Penyulang</label>
                        <select name="penyulang_id" class="select-penyulang" required>
                            <option value="">-- Pilih Penyulang --</option>
                            @foreach($penyulangs as $p)
                                <option value="{{ $p->id }}">
                                    {{ optional($p->gi)->nama_gi ?? '-' }} - {{ $p->nama_penyulang }} ({{ $p->cabang }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" required>
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" placeholder="-6.2146200">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" placeholder="106.8451300">
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">
                            Batal
                        </button>
                        <button type="submit" class="btn">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .arrester-page {
            padding: 20px;
        }

        .page-title {
            margin: 0 0 22px 0;
            font-size: 34px;
            font-weight: 800;
            color: #111827;
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

        .info-readonly {
            padding: 11px 14px;
            border-radius: 12px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid #bfdbfe;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #fff;
            font-size: 14px;
            min-width: 280px;
            min-height: 46px;
        }

        .arrester-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .arrester-table th {
            background: #0891b2;
            color: white;
            padding: 12px;
            font-weight: 800;
            text-align: center;
            border: 1px solid #d1d5db;
        }

        .arrester-table td {
            padding: 11px 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .arrester-table tbody tr:hover {
            background: #f1f5f9;
        }

        .kode-badge,
        .cabang-badge,
        .gi-badge,
        .penyulang-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 12px;
        }

        .kode-badge {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .cabang-badge {
            background: #dcfce7;
            color: #166534;
        }

        .gi-badge {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .penyulang-badge {
            background: #eef2ff;
            color: #3730a3;
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
            padding: 38px 16px;
            text-align: center;
        }

        .empty-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .empty-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            max-width: 480px;
            margin: 0 auto;
        }

        .form-group small {
            display: block;
            margin-top: 6px;
            color: #6b7280;
            font-size: 12px;
        }

        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #fff;
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
            .arrester-page {
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
            .btn,
            .info-readonly {
                width: 100%;
                max-width: 100%;
            }

            .arrester-table th,
            .arrester-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 10px 8px;
            }

            .modal-actions {
                flex-direction: column;
            }
        }

        .select2-container .select2-selection--single {
            height: 46px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
        }
    </style>

    @if($canManageArrester)
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

            $(document).ready(function () {
                $('.select-penyulang').select2({
                    width: '100%',
                    placeholder: '-- Pilih / ketik Penyulang --',
                    allowClear: true,
                    dropdownParent: $('.modal-box')
                });
            });
        </script>
    @endif
</x-app-layout>