<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Kelas_Siswa;


class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::all();
        $kelas = Kelas::all();
        return view('feature.siswa.list-siswa')->with([
            'siswa' => $siswa,
            'kelas' => $kelas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'array' // Validasi array kelas
        ]);

        $siswa = Siswa::create(['nama_siswa' => $validatedData['nama_siswa']]);

        // Simpan relasi kelas-siswa
        if ($request->has('kelas')) {
            foreach ($request->kelas as $kelas_id) {
                Kelas_Siswa::create([
                    'id_siswa' => $siswa->id,
                    'id_kelas' => $kelas_id
                ]);
            }
        }

        return redirect()->route('siswa')->with('success', 'Siswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'kelas' => 'array'
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update(['nama_siswa' => $validatedData['nama_siswa']]);

        // Hapus kelas lama dan masukkan yang baru
        Kelas_Siswa::where('id_siswa', $id)->delete();

        if ($request->has('kelas')) {
            foreach ($request->kelas as $kelas_id) {
                Kelas_Siswa::create([
                    'id_siswa' => $siswa->id,
                    'id_kelas' => $kelas_id
                ]);
            }
        }

        return redirect()->route('siswa')->with('success', 'Data siswa berhasil diperbarui!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            return redirect()->route('siswa')->with('success', 'Siswa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('siswa')->with('error', 'Gagal menghapus siswa!');
        }
    }
}
