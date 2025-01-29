@extends('template.layout')

@section('content')
    <div class="card mt-5">
        <div class="card-body">
            <h5 class="card-title">List Kelas</h5>

            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Kelas</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($kelas as $kelas_siswa)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $kelas_siswa->kelas }}</td>
                            <td><button type="button" class="btn btn-primary">Edit</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
@endsection
