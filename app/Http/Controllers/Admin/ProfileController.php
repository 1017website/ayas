<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.mixed' => 'Kata sandi baru harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Kata sandi baru harus mengandung minimal satu angka.',
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => null,
        ])->save();

        $request->session()->regenerate();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Kata sandi akun berhasil diperbarui. Silakan gunakan kata sandi baru saat login berikutnya.');
    }
}
