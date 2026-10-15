<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanController extends Controller
{
    public function index(): View
    {
        return view('pengaturan.index', ['pengaturan' => Pengaturan::aktif()]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'batas_pinjam' => ['required', 'integer', 'min:1', 'max:20'],
            'masa_pinjam' => ['required', 'integer', 'min:1', 'max:60'],
            'tarif_denda' => ['required', 'integer', 'min:0', 'max:100000'],
        ]);

        Pengaturan::aktif()->update($data);

        return back()->with('sukses', 'Pengaturan berhasil disimpan.');
    }
}