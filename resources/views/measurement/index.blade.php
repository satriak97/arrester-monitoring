<x-app-layout>
    <div class="history-page">
        <h2 class="page-title">Histori Pengukuran</h2>

        <div class="panel">
            <form method="GET" action="{{ route('measurement.history') }}" class="filter-bar">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari ID Arrester / GI / Penyulang / Lokasi..."
                    class="filter-input"
                >

                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="NORMAL" {{ request('status') == 'NORMAL' ? 'selected' : '' }}>NORMAL</option>
                    <option value="WARNING" {{ request('status') == 'WARNING' ? 'selected' : '' }}>WARNING</option>
                    <option value="FAULT" {{ request('status') == 'FAULT' ? 'selected' : '' }}>FAULT</option>
                </select>

                <button type="submit" class="btn">Filter</button>
                <a href="{{ route('measurement.history') }}" class="btn btn-secondary">Reset</a>
            </form>

            <div class="table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Arrester</th>
                            <th>GI</th>
                            <th>ID Penyulang</th>
                            <th>Penyulang</th>
                            <th>Lokasi</th>
                            <th>Koordinat</th>
                            <th>Leak Current</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($measurements as $m)
                            <tr>
                                <td>
                                    {{ ($measurements->currentPage() - 1) * $measurements->perPage() + $loop->iteration }}
                                </td>

                                <td>
                                    <span class="id-badge">
                                        {{ $m->arrester_id }}
                                    </span>
                                </td>

                                <td>
                                    <span class="gi-badge">
                                        {{ optional(optional(optional($m->arrester)->penyulang)->gi)->nama_gi ?? '-' }}
                                    </span>
                                </td>

                                <td>{{ optional(optional($m->arrester)->penyulang)->id ?? '-' }}</td>

                                <td>
                                    <span class="penyulang-badge">
                                        {{ optional(optional($m->arrester)->penyulang)->nama_penyulang ?? '-' }}
                                    </span>
                                </td>

                                <td>{{ optional($m->arrester)->lokasi ?? '-' }}</td>

                                <td>
                                    @if(optional($m->arrester)->latitude && optional($m->arrester)->longitude)
                                        {{ $m->arrester->latitude }}, {{ $m->arrester->longitude }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>{{ number_format($m->leak_current, 2) }} mA</td>

                                <td>
                                    @if($m->status === 'NORMAL')
                                        <span class="status-badge status-normal">NORMAL</span>
                                    @elseif($m->status === 'WARNING')
                                        <span class="status-badge status-warning">WARNING</span>
                                    @else
                                        <span class="status-badge status-fault">FAULT</span>
                                    @endif
                                </td>

                                <td>{{ \Carbon\Carbon::parse($m->measured_at)->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-icon">📊</div>
                                        <div class="empty-title">Belum ada data pengukuran</div>
                                        <div class="empty-text">
                                            Data monitoring akan tampil di sini setelah perangkat mengirim hasil pengukuran.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($measurements->count() > 0)
                <div class="pagination-wrapper">
                    {{ $measurements->links('vendor.pagination.pln-pagination') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .history-page {
            padding: 20px;
        }

        .page-title {
            margin: 0 0 22px 0;
            font-size: 34px;
            font-weight: 800;
            color: #111827;
        }

        .filter-bar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-input,
        .filter-select {
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #fff;
            font-size: 14px;
            min-height: 46px;
        }

        .filter-input {
            min-width: 280px;
            flex: 1;
            max-width: 360px;
        }

        .filter-select {
            min-width: 170px;
        }

        .btn-secondary {
            background: #6b7280;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .history-table th {
            background: #0891b2;
            color: white;
            padding: 12px;
            font-weight: 800;
            text-align: center;
            border: 1px solid #d1d5db;
        }

        .history-table td {
            padding: 11px 12px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .history-table tbody tr:hover {
            background: #f1f5f9;
        }

        .id-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
            font-size: 12px;
        }

        .gi-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-weight: 800;
            font-size: 12px;
        }

        .penyulang-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #eef2ff;
            color: #3730a3;
            font-weight: 800;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .status-normal {
            background: #dcfce7;
            color: #166534;
        }

        .status-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .status-fault {
            background: #fee2e2;
            color: #b91c1c;
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

        .pagination-wrapper {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .history-page {
                padding: 14px;
            }

            .page-title {
                font-size: 28px;
                margin-bottom: 16px;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-input,
            .filter-select,
            .btn,
            .btn-secondary {
                width: 100%;
                max-width: 100%;
            }

            .history-table th,
            .history-table td {
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