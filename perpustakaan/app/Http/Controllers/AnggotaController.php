<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Support\Kode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim($request->query('cari', ''));

        $daftar = Anggota::withCount('peminjaman')
            ->when($cari, fn ($q) => $q->where(fn ($inner) => $inner
                ->where('nama', 'like', "%$cari%")
                ->orWhere('kode', 'like', "%$cari%")
                ->orWhere('nik', 'like', "%$cari%")))
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        $daftar->getCollection()->each(function ($anggota) {
            $anggota->setAttribute('jumlah_aktif', $anggota->peminjamanAktif()->count());
            $anggota->setAttribute('jumlah_terlambat', $anggota->totalTerlambat());
            $anggota->setAttribute('total_denda', $anggota->totalDenda());
        });

        return view('anggota.index', compact('daftar', 'cari'));
    }

    public function form(?Anggota $anggota = null): View
    {
        return view('anggota.form', ['anggota' => $anggota ?? new Anggota]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:anggota,kode'],
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        if (empty($data['kode'])) {
            $data['kode'] = Kode::next('ANG', Anggota::class);
        }

        Anggota::create($data);

        return redirect()->route('anggota.index')->with('sukses', 'Anggota berhasil ditambahkan.');
    }

    public function ubah(Request $request, Anggota $anggota)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nik' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'tanggal_daftar' => ['required', 'date'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $anggota->update($data);

        return redirect()->route('anggota.index')->with('sukses', 'Data anggota berhasil diperbarui.');
    }

    public function hapus(Anggota $anggota)
    {
        if ($anggota->peminjaman()->exists()) {
            return back()->with('error', 'Anggota memiliki riwayat peminjaman sehingga tidak bisa dihapus.');
        }

        $anggota->delete();

        return back()->with('sukses', 'Anggota berhasil dihapus.');
    }
}