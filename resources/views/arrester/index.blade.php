<x-app-layout>
    <div class="arrester-page">
        <h2 class="page-title">Data Arrester</h2>

        <div class="panel">
            <div class="top-bar">
                <a href="{{ url('/arrester/create') }}" class="btn">Tambah Arrester</a>

                <form action="{{ url('/arrester') }}" method="GET" class="search-bar">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kode / lokasi..."
                        class="search-input"
                    >
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="arrester-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Arrester</th>
                            <th>Cabang</th>
                            <th>Lokasi</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arresters as $a)
                            <tr>
                                <td>{{ ($arresters->currentPage() - 1) * $arresters->perPage() + $loop->iteration }}</td>
                                <td>{{ $a->kode_arrester }}</td>
                                <td>{{ $a->cabang }}</td>
                                <td>{{ $a->lokasi }}</td>
                                <td>{{ $a->latitude ?? '-' }}</td>
                                <td>{{ $a->longitude ?? '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ url('/arrester/'.$a->id.'/edit') }}" class="btn btn-sm">Edit</a>

                                        <form action="{{ url('/arrester/'.$a->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
                <div class="pagination-wrapper" style="margin-top:20px;">
                    {{ $arresters->links() }}
                </div>
            @endif
        </div>
    </div>

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
            overflow: hidden;
            border-radius: 14px;
            background: #fff;
        }

        .arrester-table th,
        .arrester-table td {
            border: 1px solid #e5e7eb;
            padding: 14px 12px;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .arrester-table th {
            background: #f8fafc;
            color: #111827;
            font-weight: 700;
        }

        .arrester-table tbody tr:hover {
            background: #f9fafb;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 13px;
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

        @media (max-width: 768px) {
            .arrester-page {
                padding: 14px;
            }

            .page-title {
                font-size: 28px;
                margin-bottom: 16px;
            }

            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-bar {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .search-input,
            .btn {
                width: 100%;
                max-width: 100%;
            }

            .arrester-table th,
            .arrester-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 10px 8px;
            }

            .empty-title {
                font-size: 18px;
            }
        }
    </style>
</x-app-layout>