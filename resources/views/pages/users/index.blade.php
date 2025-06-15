<x-app-layout title="Kelola Users">
    <div class="container mt-4">
        <h1 class="mb-4">Form User</h1>
        <form id="userForm" action="{{ route('kelola-users.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('POST')
            <input type="hidden" name="id" id="id">
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" required placeholder="Masukkan NIK" value="{{ old('nik') }}">
                <div class="invalid-feedback">
                    @error('nik')
                        {{ $message }}
                    @else
                        NIK wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" required placeholder="Masukkan nama" value="{{ old('name') }}">
                <div class="invalid-feedback">
                    @error('name')
                        {{ $message }}
                    @else
                        Nama wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="Masukkan email" value="{{ old('email') }}">
                <div class="invalid-feedback">
                    @error('email')
                        {{ $message }}
                    @else
                        Email wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" value="{{ old('password') }}">
                <div class="invalid-feedback">
                    @error('password')
                        {{ $message }}
                    @else
                        Password wajib diisi.
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pasien" {{ old('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                    <option value="dokter" {{ old('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                </select>
                <div class="invalid-feedback">
                    @error('role')
                        {{ $message }}
                    @else
                        Role wajib diisi.
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
        </form>

        <!-- Tabel DataTables -->
        <h2 class="mt-5 mb-3">Daftar User</h2>
        <table id="userTable" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->nik }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editUser({{ $user->id }}, '{{ $user->nik }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery and DataTables CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
        });

        function resetForm() {
            $('#userForm')[0].reset();
            $('#userForm').attr('action', '{{ route('kelola-users.store') }}');
            $('#userForm').find('input[name="_method"]').val('POST');
            $('#id').val('');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').hide();
        }

        function editUser(id, nik, name, email, role) {
            $('#id').val(id);
            $('#nik').val(nik);
            $('#name').val(name);
            $('#email').val(email);
            $('#role').val(role);
            $('#password').val(''); // Kosongkan password saat edit
            $('#userForm').attr('action', '{{ url('kelola-users') }}/' + id);
            $('#userForm').find('input[name="_method"]').val('PUT');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data user akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('kelola-users') }}/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'User berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus user.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: '@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        @endif
    </script>
</x-app-layout>