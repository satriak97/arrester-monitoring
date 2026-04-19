<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title" style="margin-bottom: 20px;">
            Histori Pengukuran Arrester: {{ $arrester_id }}
        </h2>

        <div class="panel">
            <a href="{{ route('dashboard') }}" class="btn" style="margin-bottom:15px;">Kembali ke Dashboard</a>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Arrester</th>
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
                                <td>{{ ($measurements->currentPage() - 1) * $measurements->perPage() + $loop->iteration }}</td>
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
                                        <span class="status-normal">NORMAL</span>
                                    @elseif($m->status === 'WARNING')
                                        <span class="status-warning">WARNING</span>
                                    @else
                                        <span class="status-fault">FAULT</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($m->measured_at)->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Belum ada histori pengukuran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper" style="margin-top:20px;">
                {{ $measurements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>