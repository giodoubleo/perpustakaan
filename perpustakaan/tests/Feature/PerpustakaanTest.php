<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Pengaturan;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_halaman_login_dapat_diakses(): void
    {
        $this->get('/login')->assertOk()->assertSee('Masuk Akun');
    }

    public function test_beranda_publik_dapat_diakses_tamu(): void
    {
        $this->get('/')->assertOk()->assertSee(config('app.name'));
    }

    public function test_admin_dapat_login(): void
    {
        $response = $this->post('/login', ['username' => 'admin', 'password' => 'admin123']);

        $this->assertAuthenticatedAs(User::where('username', 'admin')->first());
        $response->assertRedirect(route('dashboard'));
    }

    public function test_password_salah_ditolak(): void
    {
        $this->post('/login', ['username' => 'admin', 'password' => 'salah']);

        $this->assertGuest();
    }

    public function test_anggota_tidak_bisa_membuka_halaman_buku(): void
    {
        $this->actingAs(User::where('username', 'anggota1')->first())
            ->get('/buku')
            ->assertForbidden();
    }

    public function test_staff_tidak_bisa_membuka_manajemen_user(): void
    {
        $this->actingAs(User::where('username', 'staff')->first())
            ->get('/pengguna')
            ->assertForbidden();
    }

    public function test_peminjaman_berhasil_dicatat_dan_menurunkan_tersedia(): void
    {
        $buku = Buku::first();
        $sebelum = $buku->tersedia();

        $this->actingAs(User::where('username', 'staff')->first())
            ->post('/peminjaman', ['anggota_id' => Anggota::where('kode', 'ANG-0002')->value('id'), 'buku_id' => $buku->id])
            ->assertRedirect(route('peminjaman.index'));

        $this->assertDatabaseHas('peminjaman', ['buku_id' => $buku->id, 'status' => 'dipinjam']);
        $this->assertSame($sebelum - 1, $buku->fresh()->tersedia());
    }

    public function test_peminjaman_ditolak_saat_batas_pinjam_terlampaui(): void
    {
        $staff = User::where('username', 'staff')->first();
        $anggota = Anggota::where('kode', 'ANG-0002')->first();
        $batas = Pengaturan::aktif()->batas_pinjam;

        foreach (Buku::take($batas)->get() as $buku) {
            Peminjaman::create([
                'kode' => 'PMJ-' . $buku->id . '01',
                'tanggal_pinjam' => today(),
                'tanggal_batas' => today()->addDays(7),
                'anggota_id' => $anggota->id,
                'buku_id' => $buku->id,
                'judul' => $buku->judul,
                'status' => 'dipinjam',
            ]);
        }

        $sisa = Buku::skip($batas)->first();

        $this->actingAs($staff)
            ->post('/peminjaman', ['anggota_id' => $anggota->id, 'buku_id' => $sisa->id])
            ->assertSessionHas('error');
    }

    public function test_pengembalian_menghitung_denda(): void
    {
        $transaksi = Peminjaman::where('kode', 'PMJ-0001')->first();
        $transaksi->update(['tanggal_batas' => today()->subDays(3)]);

        $this->actingAs(User::where('username', 'staff')->first())
            ->put('/pengembalian/' . $transaksi->id, ['tanggal_kembali' => today()->toDateString()])
            ->assertRedirect(route('pengembalian.index'));

        $transaksi->refresh();

        $this->assertSame('selesai', $transaksi->status);
        $this->assertSame(3, $transaksi->hari_terlambat);
        $this->assertSame(3000, $transaksi->denda);
    }

    public function test_halaman_laporan_dapat_diakses_admin(): void
    {
        $this->actingAs(User::where('username', 'admin')->first())
            ->get('/laporan')
            ->assertOk()
            ->assertSee('Laporan Perpustakaan');
    }
}