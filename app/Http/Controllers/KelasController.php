<?php

namespace App\Http\Controllers;

use App\Models\kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function showListKelas() {
        $kelas = kelas::all();
        return view('feature.list-kelas')->with('kelas', $kelas);
    }

    public function addKelas(Request $request) {
        $validatedData = $request->validate([
            'kelas' => 'required|string|max:255'
        ]);

        kelas::create($validatedData);

        return redirect()->route('list-kelas')->with('success', 'Kelas berhasil ditambahkan!');
    }
}
