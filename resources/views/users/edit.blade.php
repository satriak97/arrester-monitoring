<x-app-layout>
    <div style="padding: 20px;">
        <h2 class="page-title">Edit User</h2>

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

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px;" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin_cabang" {{ old('role', $user->role) == 'admin_cabang' ? 'selected' : '' }}>Admin Cabang</option>
                    </select>
                </div>

                <!-- <div class="form-group">
                    <label>Cabang</label>
                    <input type="text" name="cabang" value="{{ old('cabang', $user->cabang) }}" required>
                </div> -->

                <div class="form-group">
                    <label>Admin Cabang</label>
                    <select name="admin_cabang_id">
                        <option value="">-- Pilih Admin Cabang --</option>
                        @foreach($adminCabangs as $adminCabang)
                            <option value="{{ $adminCabang->id }}"
                                {{ old('admin_cabang_id', $user->admin_cabang_id) == $adminCabang->id ? 'selected' : '' }}>
                                {{ $adminCabang->name }} - {{ $adminCabang->cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Password Baru (opsional)</label>
                    <input type="password" name="password">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation">
                </div>

                <button type="submit" class="btn">Update</button>
                <a href="{{ route('users.index') }}" class="btn" style="background:#6b7280;">Kembali</a>
            </form>
        </div>
    </div>
</x-app-layout>