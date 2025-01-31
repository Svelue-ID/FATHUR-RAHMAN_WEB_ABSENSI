<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Kelas_Siswa;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function listAbsen($kelas) {
        $kelas = Kelas::findOrFail($kelas); 
        $absenGroups = Absen::where('id_kelas', $kelas->id)
            ->selectRaw('tanggal, dokumentasi_kehadiran, 
                SUM(CASE WHEN keterangan_hadir = "hadir" THEN 1 ELSE 0 END) as jumlah_hadir,
                SUM(CASE WHEN keterangan_hadir = "tidak_hadir" THEN 1 ELSE 0 END) as jumlah_tidak_hadir')
            ->groupBy('tanggal', 'dokumentasi_kehadiran')
            ->get();
    
        return view('feature.absen.absen', compact('absenGroups', 'kelas'));
    }
    
    public function showCreateForm($kelas) {
        $kelas = Kelas::findOrFail($kelas);
        return view('feature.absen.create', compact('kelas'));
    }
    
    public function storeAbsen(Request $request, $kelas) {
        $request->validate([
            'tanggal' => 'required|date',
            'dokumentasi' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'siswa' => 'required|array',
            'siswa.*.id_siswa' => 'required|exists:siswa,id',
            'siswa.*.status' => 'required|in:hadir,tidak_hadir',
        ]);
    
        $dokumentasiPath = $request->file('dokumentasi')->store('absen-dokumentasi', 'public');
    
        $absenData = [];
        foreach ($request->siswa as $siswa) {
            $absenData[] = [
                'id_kelas' => $kelas, 
                'id_siswa' => $siswa['id_siswa'],
                'tanggal' => $request->tanggal,
                'keterangan_hadir' => $siswa['status'],
                'dokumentasi_kehadiran' => $dokumentasiPath,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    
        Absen::insert($absenData);
    
        return redirect()->route('absen', $kelas)->with('success', 'Absen berhasil disimpan!');
    }

    public function getSiswaByKelas($kelas) {
        $students = Kelas_Siswa::with('siswa')
            ->where('id_kelas', $kelas)
            ->get()
            ->map(function ($item) {
                if ($item->siswa) {
                    return [
                        'id' => $item->siswa->id,
                        'nama' => $item->siswa->nama_siswa,
                    ];
                }
                return null;
            })
            ->filter();
    
        return response()->json($students);
    }
}