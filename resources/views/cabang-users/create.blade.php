<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title">Tambah User Cabang</h2>

        <div class="panel">
            @if ($errors->any())
                <div style="background:#fee2e2; color:#991b1b; padding:12px; border-radius:8px; margin-bottom:15px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="margin-bottom: 15px; font-weight: 600;">
                Cabang otomatis: {{ auth()->user()->cabang }}
            </div>

            <form action="{{ route('cabang-users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn">Simpan</button>
                <a href="{{ route('cabang-users.index') }}" class="btn" style="background:#6b7280;">Kembali</a>
            </form>
        </div>
    </div>
</x-app-layout>