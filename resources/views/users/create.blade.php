<x-app-layout>
    <div class="form-container">
        <h2 class="page-title">Tambah User</h2>

        <div class="panel">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role">
                            <option value="user">User</option>
                            <option value="admin_cabang">Admin Cabang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Cabang</label>
                        <input type="text" name="cabang">
                    </div>

                </div>

                <div class="form-actions">
                    <a href="/users" class="btn btn-secondary">Kembali</a>
                    <button class="btn">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>