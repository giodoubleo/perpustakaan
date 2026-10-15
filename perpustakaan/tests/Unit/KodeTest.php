<?php

namespace Tests\Unit;

use App\Support\Kode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_membuat_kode_pertama_kali_mulai_dari_satu(): void
    {
        $this->assertSame('ANG-0001', Kode::next('ANG', \App\Models\Anggota::class));
    }

    public function test_kode_berikutnya_naik_satu(): void
    {
        \App\Models\Anggota::create([
            'kode' => 'ANG-0001',
            'nama' => 'Budi Santoso',
            'gender' => 'L',
            'tanggal_daftar' => '2024-01-15',
            'status' => 'aktif',
        ]);

        $this->assertSame('ANG-0002', Kode::next('ANG', \App\Models\Anggota::class));
    }

    public function test_lewat_kode_dengan_nomor_lebih_besar(): void
    {
        \App\Models\Anggota::create([
            'kode' => 'ANG-0001',
            'nama' => 'Budi Santoso',
            'gender' => 'L',
            'tanggal_daftar' => '2024-01-15',
            'status' => 'aktif',
        ]);
        \App\Models\Anggota::create([
            'kode' => 'ANG-0007',
            'nama' => 'Siti Nurhaliza',
            'gender' => 'P',
            'tanggal_daftar' => '2024-02-20',
            'status' => 'aktif',
        ]);

        $this->assertSame('ANG-0008', Kode::next('ANG', \App\Models\Anggota::class));
    }

    public function test_dari_model_mengambil_prefix_dari_model(): void
    {
        $anggota = new \App\Models\Anggota();

        $this->assertSame('ANG-0001', Kode::dariModel($anggota, 'ANG'));
    }
}