<x-app-layout>
    <div class="form-container">
        <h2 class="page-title">Tambah Arrester</h2>

        <div class="panel">

            @if ($errors->any())
                <div class="alert-fault">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/arrester') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nomor Arrester</label>
                        <input type="number" name="nomor_arrester" required>
                    </div>

                    @if(auth()->user()->role === 'admin_master')
                        <div class="form-group">
                            <label>Cabang</label>
                            <input type="text" name="cabang" required>
                        </div>
                    @else
                        <div class="form-group">
                            <label>Cabang</label>
                            <input type="text" value="{{ auth()->user()->cabang }}" disabled>
                        </div>
                    @endif

                    <div class="form-group full">
                        <label>Lokasi</label>
                        <input type="text" name="lokasi" required>
                    </div>

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude">
                    </div>

                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude">
                    </div>

                </div>

                <div class="form-actions">
                    <a href="/arrester" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>