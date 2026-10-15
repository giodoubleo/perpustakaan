<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BukuController extends Controller
{
    public function index(Request $request): View
    {
        $cari = trim($request->query('cari', ''));
        $kategori = $request->query('kategori', '');

        $daftar = Buku::withCount('peminjaman')
            ->when($cari, fn ($q) => $q->where(fn ($inner) => $inner
                ->where('judul', 'like', "%$cari%")
                ->orWhere('penulis', 'like', "%$cari%")
                ->orWhere('isbn', 'like', "%$cari%")
                ->orWhere('kode', 'like', "%$cari%")))
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        $daftar->getCollection()->each(function ($buku) {
            $buku->setAttribute('jumlah_dipinjam', $buku->peminjaman->where('status', 'dipinjam')->count());
            $buku->setAttribute('jumlah_tersedia', max(0, $buku->stok - $buku->jumlah_dipinjam));
        });

        $kategoriList = Buku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('buku.index', compact('daftar', 'kategoriList', 'cari', 'kategori'));
    }

    public function form(?Buku $buku = null): View
    {
        return view('buku.form', ['buku' => $buku ?? new Buku]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:buku,kode'],
            'isbn' => ['nullable', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['required', 'string', 'max:100'],
            'penerbit' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:1'],
            'lokasi' => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($data['kode'])) {
            $data['kode'] = \App\Support\Kode::next('BK', Buku::class);
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil ditambahkan.');
    }

    public function ubah(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'isbn' => ['nullable', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:150'],
            'penulis' => ['required', 'string', 'max:100'],
            'penerbit' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:1'],
            'lokasi' => ['nullable', 'string', 'max:50'],
        ]);

        $dipinjam = $buku->dipinjam();

        if ($data['stok'] < $dipinjam) {
            return back()->with('error', "Stok tidak boleh lebih kecil dari buku yang sedang dipinjam ($dipinjam).");
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil diperbarui.');
    }

    public function hapus(Buku $buku)
    {
        if ($buku->dipinjam() > 0) {
            return back()->with('error', 'Buku masih dipinjam sehingga tidak bisa dihapus.');
        }

        $buku->delete();

        return back()->with('sukses', 'Buku berhasil dihapus.');
    }
}