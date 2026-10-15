<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $daftar = User::with('anggota')->orderBy('role')->orderBy('name')->get();

        return view('user.index', compact('daftar'));
    }

    public function form(?User $user = null): View
    {
        return view('user.form', ['user' => $user ?? new User]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:30', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'staff', 'anggota'])],
            'telepon' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'anggota_id' => ['nullable', 'exists:anggota,id'],
        ]);

        User::create($data);

        return redirect()->route('user.index')->with('sukses', 'Akun pengguna berhasil dibuat.');
    }

    public function ubah(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'staff', 'anggota'])],
            'telepon' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'anggota_id' => ['nullable', 'exists:anggota,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('sukses', 'Akun pengguna berhasil diperbarui.');
    }

    public function hapus(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        }

        $user->delete();

        return back()->with('sukses', 'Akun pengguna berhasil dihapus.');
    }
}