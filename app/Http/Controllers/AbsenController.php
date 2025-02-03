<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Kelas_Siswa;
use App\Exports\AbsenExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function downloadAbsen($kelas, $tanggal)
{
    $kelas = Kelas::findOrFail($kelas);

    // Ambil data absensi berdasarkan kelas dan tanggal
    $absenData = Absen::where('id_kelas', $kelas->id)
        ->where('tanggal', $tanggal)
        ->with('siswa') // Mengambil relasi siswa
        ->get();

    if ($absenData->isEmpty()) {
        return back()->with('error', 'Data absen tidak ditemukan.');
    }

    // Hitung total hadir dan tidak hadir
    $totalHadir = $absenData->where('keterangan_hadir', 'hadir')->count();
    $totalTidakHadir = $absenData->where('keterangan_hadir', 'tidak_hadir')->count();

    $filename = "absensi_{$kelas->kelas}_{$tanggal}.csv";

    $response = new StreamedResponse(function () use ($absenData, $totalHadir, $totalTidakHadir) {
        $handle = fopen('php://output', 'w');

        // Pastikan file CSV menggunakan format UTF-8 agar tidak ada masalah encoding
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header kolom
        fputcsv($handle, ['Tanggal', 'Jumlah Hadir', 'Jumlah Tidak Hadir']);
        fputcsv($handle, [$absenData->first()->tanggal, $totalHadir, $totalTidakHadir]);

        // Tambahkan header untuk daftar siswa
        fputcsv($handle, ['Tanggal', 'Nama Siswa', 'Status Kehadiran']);

        // Loop untuk setiap data absen
        foreach ($absenData as $absen) {
            fputcsv($handle, [
                $absen->tanggal,
                $absen->siswa->nama_siswa, // Ambil nama siswa dari relasi
                ucfirst($absen->keterangan_hadir), // Hadir/Tidak Hadir
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    return $response;
    }
}
