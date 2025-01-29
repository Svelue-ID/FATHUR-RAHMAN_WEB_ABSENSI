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
}
