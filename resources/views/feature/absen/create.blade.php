@extends('template.layout')

@section('content')
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Tambah Absen Baru - Kelas {{ $kelas->kelas }}</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <form action="{{ route('absen.create', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" value="{{ $kelas->kelas }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="dokumentasi" class="form-label">Dokumentasi</label>
                        <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody id="siswaTableBody">
                            <!-- Data siswa akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Absen</button>
            </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil ID kelas dari URL
            const pathSegments = window.location.pathname.split('/');
            const kelasId = pathSegments[pathSegments.indexOf('absen') + 1];

            fetch(`/get-siswa-by-kelas/${kelasId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Gagal memuat siswa');
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('siswaTableBody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada siswa di kelas ini</td>
                        </tr>
                    `;
                        return;
                    }

                    data.forEach(siswa => {
                        const row = `
                        <tr>
                            <td>${siswa.nama}</td>
                            <td>
                                <input type="hidden" name="siswa[${siswa.id}][id_siswa]" value="${siswa.id}">
                                <select name="siswa[${siswa.id}][status]" class="form-select">
                                    <option value="hadir">Hadir</option>
                                    <option value="tidak_hadir">Tidak Hadir</option>
                                </select>
                            </td>
                        </tr>
                    `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const tbody = document.getElementById('siswaTableBody');
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center text-danger">${error.message}</td>
                    </tr>
                `;
                });
        });
    </script>
@endpush
