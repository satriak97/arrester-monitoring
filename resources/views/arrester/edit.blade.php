<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title">Edit Arrester</h2>

        <div class="panel">

            @if ($errors->any())
                <div style="background:#fdecea; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $prefix = substr($arrester->kode_arrester, 0, 1);
                $nomorBelakang = substr($arrester->kode_arrester, 1);
            @endphp

            <form action="{{ url('/arrester/'.$arrester->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- KODE ARRESTER HASIL --}}
                <div class="form-group">
                    <label>Kode Arrester Saat Ini</label>
                    <input type="text" value="{{ $arrester->kode_arrester }}" disabled>
                </div>

                {{-- PREFIX CABANG --}}
                <div class="form-group">
                    <label>Prefix Cabang</label>
                    <input type="text" value="{{ $prefix }}" disabled>
                </div>

                {{-- NOMOR ARRESTER --}}
                <div class="form-group">
                    <label>Nomor Arrester</label>
                    <input type="number" name="nomor_arrester"
                           value="{{ old('nomor_arrester', $nomorBelakang) }}"
                           min="1" max="9999" required>
                    <small>Nomor belakang diisi manual. Kode akhir akan menjadi 5 digit sesuai cabang.</small>
                </div>

                {{-- CABANG --}}
                @if(auth()->user()->role === 'admin_master')
                    <div class="form-group">
                        <label>Cabang</label>
                        <input type="text" name="cabang" value="{{ old('cabang', $arrester->cabang) }}" required>
                    </div>
                @else
                    <div class="form-group">
                        <label>Cabang</label>
                        <input type="text" value="{{ auth()->user()->cabang }}" disabled>
                    </div>
                @endif

                {{-- PENYULANG --}}
                <div class="form-group">
                    <label>Penyulang</label>

                    <select name="penyulang_id">
                        <option value="">-- Pilih Penyulang --</option>

                        @foreach($penyulangs as $p)
                            <option value="{{ $p->id }}"
                                {{ $a->penyulang_id == $p->id ? 'selected' : '' }}>
                                {{ $p->gi->nama_gi }} - {{ $p->nama_penyulang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- LOKASI --}}
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi"
                           value="{{ old('lokasi', $arrester->lokasi) }}" required>
                </div>

                {{-- KOORDINAT --}}
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="text" name="latitude"
                           value="{{ old('latitude', $arrester->latitude) }}"
                           placeholder="-6.2146200">
                </div>

                <div class="form-group">
                    <label>Longitude</label>
                    <input type="text" name="longitude"
                           value="{{ old('longitude', $arrester->longitude) }}"
                           placeholder="106.8451300">
                </div>

                <button type="submit" class="btn">Update</button>
                <a href="{{ url('/arrester') }}" class="btn" style="background:#6b7280;">Kembali</a>
            </form>

        </div>
    </div>
</x-app-layout>