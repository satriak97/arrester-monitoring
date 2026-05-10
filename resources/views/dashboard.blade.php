<x-app-layout>
    <div class="dashboard-page">
        <div class="dashboard-header">
            <div>
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">
                    Sistem Monitoring Leakage Current Arrester
                </p>
            </div>

            <div class="dashboard-meta">
                <span id="lastUpdate">Update terakhir: -</span>
            </div>
        </div>

        @if($fault > 0)
            <div class="alert-fault">
                ⚠️ Peringatan: Terdapat {{ $fault }} arrester dalam kondisi FAULT.
            </div>
        @endif

        <div class="stats-grid">
            <div class="stat-card stat-normal">
                <div class="stat-label">Normal</div>
                <div class="stat-value" id="normalCount">{{ $normal }}</div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-label">Warning</div>
                <div class="stat-value" id="warningCount">{{ $warning }}</div>
            </div>

            <div class="stat-card stat-fault">
                <div class="stat-label">Fault</div>
                <div class="stat-value" id="faultCount">{{ $fault }}</div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <h3 class="section-title">Map Monitoring Arrester</h3>

                <select id="mapStatusFilter" class="filter-select">
                    <option value="ALL">Semua Status</option>
                    <option value="NORMAL">Normal</option>
                    <option value="WARNING">Warning</option>
                    <option value="FAULT">Fault</option>
                    <option value="NO_DATA">Belum Ada Data</option>
                </select>
            </div>

            <div id="map" class="map-box"></div>
        </div>

        <div class="panel">
            <h3 class="section-title">Grafik Leakage Current</h3>
            <div class="chart-box">
                <canvas id="chartLeak"></canvas>
            </div>
        </div>

        <div class="panel">
            <h3 class="section-title">Tabel Monitoring</h3>

            <div class="table-wrapper">
                <table class="monitoring-table">
                    <thead>
                        <tr>
                            <th>ID Arrester</th>
                            <th>Lokasi</th>
                            <th>Koordinat</th>
                            <th>Leak Current</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable">
                        @forelse($measurements as $m)
                            <tr>
                                <td>{{ $m->arrester_id }}</td>
                                <td>{{ optional($m->arrester)->lokasi ?? '-' }}</td>
                                <td>
                                    @if(optional($m->arrester)->latitude && optional($m->arrester)->longitude)
                                        {{ $m->arrester->latitude }}, {{ $m->arrester->longitude }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $m->leak_current }} mA</td>
                                <td>
                                    @if($m->status === 'NORMAL')
                                        <span class="status-badge status-normal">NORMAL</span>
                                    @elseif($m->status === 'WARNING')
                                        <span class="status-badge status-warning">WARNING</span>
                                    @elseif($m->status === 'FAULT')
                                        <span class="status-badge status-fault">FAULT</span>
                                    @else
                                        <span class="status-badge status-no-data">BELUM ADA DATA</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($m->measured_at)->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">📡</div>
                                        <div class="empty-title">Belum ada data monitoring</div>
                                        <div class="empty-text">
                                            Data akan tampil setelah perangkat mengirim pengukuran ke server.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        .dashboard-page {
            padding: 4px 0 8px 0;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .dashboard-title {
            margin: 0;
            font-size: 34px;
            font-weight: 800;
            color: #111827;
        }

        .dashboard-subtitle {
            margin: 6px 0 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .dashboard-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #6b7280;
            padding-top: 8px;
        }

        .alert-fault {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .stat-card {
            border-radius: 18px;
            padding: 22px;
            color: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            opacity: 0.95;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 34px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-normal {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .stat-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-fault {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .section-title {
            margin: 0 0 14px 0;
            font-size: 22px;
            font-weight: 800;
            color: #111827;
        }

        .filter-select {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background: white;
            min-width: 170px;
        }

        .map-box {
            height: 430px;
            border-radius: 14px;
            overflow: hidden;
        }

        .chart-box {
            height: 330px;
        }

        .monitoring-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .monitoring-table th,
        .monitoring-table td {
            border: 1px solid #e5e7eb;
            padding: 14px 12px;
            text-align: center;
            font-size: 14px;
            vertical-align: middle;
        }

        .monitoring-table th {
            background: #f8fafc;
            font-weight: 700;
        }

        .monitoring-table tbody tr:hover {
            background: #f9fafb;
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

        .status-no-data {
            background: #e5e7eb;
            color: #4b5563;
        }

        .leaflet-popup-content {
            line-height: 1.6;
            font-size: 14px;
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

        .legend-box {
            background: white;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 12px;
            line-height: 1.7;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .map-box {
                height: 320px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 28px;
            }

            .dashboard-meta {
                flex-direction: column;
                gap: 6px;
                padding-top: 0;
            }

            .filter-select {
                width: 100%;
            }

            .chart-box {
                height: 240px;
            }

            .map-box {
                height: 260px;
            }

            .monitoring-table th,
            .monitoring-table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 10px 8px;
            }
        }
    </style>

    <script>
        const initialMeasurements = @json($measurements);
        const initialMapData = @json($mapData);

        const arresterLookup = {};
        initialMapData.forEach(item => {
            arresterLookup[item.kode_arrester] = item;
        });

        function getStatusFromLeakCurrent(value) {
            const leak = parseFloat(value);

            if (leak < 1) return 'NORMAL';
            if (leak <= 10) return 'WARNING';
            return 'FAULT';
        }

        function normalizeRealtimeRows(rows) {
            return rows.map(row => {
                const arrester = arresterLookup[row.arrester_id] ?? {};

                return {
                    ...row,
                    lokasi: row.lokasi && row.lokasi !== '-' ? row.lokasi : (arrester.lokasi ?? '-'),
                    latitude: row.latitude ?? arrester.latitude ?? null,
                    longitude: row.longitude ?? arrester.longitude ?? null,
                    status: row.status && row.status.trim() !== ''
                        ? row.status
                        : getStatusFromLeakCurrent(row.leak_current),
                };
            });
        }

        const ctx = document.getElementById('chartLeak');

        const chartLeak = new Chart(ctx, {
            type: 'line',
            data: {
                labels: initialMeasurements.map(item => item.measured_at),
                datasets: [
                    {
                        label: 'Leakage Current (mA)',
                        data: initialMeasurements.map(item => item.leak_current),
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false,
                        pointRadius: 3
                    },
                    {
                        label: 'Warning Threshold',
                        data: Array(initialMeasurements.length).fill(1),
                        borderDash: [6, 6],
                        borderWidth: 1.5,
                        pointRadius: 0
                    },
                    {
                        label: 'Fault Threshold',
                        data: Array(initialMeasurements.length).fill(10),
                        borderDash: [6, 6],
                        borderWidth: 1.5,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'mA'
                        }
                    }
                }
            }
        });

        function getStatusClass(status) {
            const s = (status || '').toString().trim().toUpperCase();
            if (s === 'NORMAL') return 'status-normal';
            if (s === 'WARNING') return 'status-warning';
            if (s === 'FAULT') return 'status-fault';
            return 'status-no-data';
        }

        function formatStatus(status) {
            const s = (status || '').toString().trim().toUpperCase();
            if (s === 'NO_DATA') return 'BELUM ADA DATA';
            if (!s) return '-';
            return s;
        }

        function renderTable(rows) {
            let tableHtml = '';

            rows.forEach(row => {
                const koordinat = (row.latitude && row.longitude)
                    ? `${row.latitude}, ${row.longitude}`
                    : '-';

                tableHtml += `
                    <tr>
                        <td>${row.arrester_id}</td>
                        <td>${row.lokasi ?? '-'}</td>
                        <td>${koordinat}</td>
                        <td>${row.leak_current} mA</td>
                        <td>
                            <span class="status-badge ${getStatusClass(row.status)}">
                                ${formatStatus(row.status)}
                            </span>
                        </td>
                        <td>${row.measured_at}</td>
                    </tr>
                `;
            });

            if (!tableHtml) {
                tableHtml = `
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">📡</div>
                                <div class="empty-title">Belum ada data monitoring</div>
                                <div class="empty-text">
                                    Data akan tampil setelah perangkat mengirim pengukuran ke server.
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }

            document.getElementById('dataTable').innerHTML = tableHtml;
        }

        renderTable(normalizeRealtimeRows(initialMeasurements));

        const map = L.map('map').setView([-6.2, 106.8], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            const div = L.DomUtil.create('div', 'legend-box');
            div.innerHTML = `
                <b>Status</b><br>
                🟢 Normal<br>
                🟠 Warning<br>
                🔴 Fault<br>
                ⚪ Belum Ada Data
            `;
            return div;
        };
        legend.addTo(map);

        let markerLayer = L.layerGroup().addTo(map);

        function normalizeStatus(status) {
            return (status || '').toString().trim().toUpperCase();
        }

        function getMarkerColor(status) {
            const s = normalizeStatus(status);
            if (s === 'FAULT') return 'red';
            if (s === 'WARNING') return 'orange';
            if (s === 'NORMAL') return 'green';
            return 'gray';
        }

        function createCircleMarker(item) {
            const color = getMarkerColor(item.status);

            const marker = L.circleMarker([item.latitude, item.longitude], {
                radius: 8,
                color: color,
                fillColor: color,
                fillOpacity: 0.85,
                weight: 2
            });

            marker.bindPopup(`
                <div style="min-width:220px;">
                    <b>ID Arrester:</b> ${item.kode_arrester}<br>
                    <b>Lokasi:</b> ${item.lokasi ?? '-'}<br>
                    <b>Status:</b> ${formatStatus(item.status)}<br>
                    <b>Leak Current:</b> ${item.leak_current !== null ? item.leak_current + ' mA' : '-'}<br>
                    <b>Waktu:</b> ${item.measured_at ?? '-'}<br><br>

                    <a href="/measurement-history/${item.kode_arrester}" 
                    style="display:inline-block; padding:6px 10px; background:#2563eb; color:white; text-decoration:none; border-radius:6px; margin-right:6px;">
                        Lihat Histori
                    </a>

                    <a href="https://www.google.com/maps?q=${item.latitude},${item.longitude}" 
                    target="_blank"
                    style="display:inline-block; padding:6px 10px; background:#16a34a; color:white; text-decoration:none; border-radius:6px;">
                        Google Maps
                    </a>
                </div>
            `);

            return marker;
        }

        function renderMarkers(data, statusFilter = 'ALL') {
            markerLayer.clearLayers();

            const bounds = [];
            const filter = normalizeStatus(statusFilter);

            data.forEach(item => {
                if (!item.latitude || !item.longitude) return;

                const itemStatus = normalizeStatus(item.status);

                if (filter !== 'ALL' && itemStatus !== filter) return;

                const marker = createCircleMarker(item);
                markerLayer.addLayer(marker);
                bounds.push([item.latitude, item.longitude]);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        }

        function loadRealtimeData() {
            fetch('/api/measurement/latest')
                .then(response => response.json())
                .then(data => {
                    const rows = normalizeRealtimeRows([...data].reverse());

                    const labels = rows.map(row => row.measured_at);
                    const values = rows.map(row => row.leak_current);

                    renderTable(rows);

                    const now = new Date();
                    document.getElementById('lastUpdate').textContent =
                        'Update terakhir: ' + now.toLocaleTimeString();

                    chartLeak.data.labels = labels;
                    chartLeak.data.datasets[0].data = values;
                    chartLeak.data.datasets[1].data = Array(values.length).fill(1);
                    chartLeak.data.datasets[2].data = Array(values.length).fill(10);
                    chartLeak.update();
                })
                .catch(error => {
                    console.error('Gagal mengambil data realtime:', error);
                });
        }

        document.getElementById('mapStatusFilter').addEventListener('change', function () {
            renderMarkers(initialMapData, this.value);
        });

        renderMarkers(initialMapData, 'ALL');

        const now = new Date();
        document.getElementById('lastUpdate').textContent =
            'Update terakhir: ' + now.toLocaleTimeString();

        loadRealtimeData();
        setInterval(loadRealtimeData, 3000);
    </script>
</x-app-layout>