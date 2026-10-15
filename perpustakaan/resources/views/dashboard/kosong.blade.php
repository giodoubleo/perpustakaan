@extends('layouts.app')

@section('judul', 'Dashboard')
@section('halaman', 'Dashboard')

@section('konten')
    <div class="kartu animasi-muncul">
        <x-kosong :pesan="$pesan . ' Silakan hubungi administrator perpustakaan.'" ikon="🪪" />
    </div>
@endsection