@extends('template.layout')

@section('content')
    <button type="button" class="btn btn-primary mt-4" id="openModal">
        Tambah Siswa
    </button>

    {{-- Tmabah Modal --}}
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('create-siswa.submit') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_siswa" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Pilih Kelas</label>
                            <select class="form-control" id="kelas" name="kelas[]" multiple>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editSiswaModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSiswaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_siswa" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="edit_nama_siswa" name="nama_siswa" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kelas" class="form-label">Pilih Kelas</label>
                            <select class="form-control" id="edit_kelas" name="kelas[]" multiple>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Konfirmasi Pengehapusan Modal --}}
    <div class="modal fade" id="deleteSiswaModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteSiswaForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus siswa ini: <strong id="siswaName"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-2">
        <div class="card-body">
            <h5 class="card-title">List Siswa</h5>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Siswa</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $list_siswa)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $list_siswa->nama_siswa }}</td>
                            <td>
                                <button type="button" class="btn btn-warning edit-btn" data-id="{{ $list_siswa->id }}"
                                    data-nama="{{ $list_siswa->nama_siswa }}">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-danger delete-btn" data-id="{{ $list_siswa->id }}"
                                    data-nama="{{ $list_siswa->nama_siswa }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("openModal").addEventListener("click", function() {
                var modal = new bootstrap.Modal(document.getElementById("tambahSiswaModal"));
                modal.show();
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const siswaId = this.getAttribute('data-id');
                    const siswaNama = this.getAttribute('data-nama');

                    const form = document.getElementById('editSiswaForm');
                    form.action = `/edit-siswa/${siswaId}`;

                    document.getElementById('edit_nama_siswa').value = siswaNama;

                    var modal = new bootstrap.Modal(document.getElementById('editSiswaModal'));
                    modal.show();
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const siswaId = this.getAttribute('data-id');
                    const siswaNama = this.getAttribute('data-nama');

                    const form = document.getElementById('deleteSiswaForm');
                    form.action = `/delete-siswa/${siswaId}`;

                    document.getElementById('siswaName').textContent = siswaNama;

                    var modal = new bootstrap.Modal(document.getElementById('deleteSiswaModal'));
                    modal.show();
                });
            });
        });
    </script>
@endpush
