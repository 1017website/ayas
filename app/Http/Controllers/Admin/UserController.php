<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', ['users' => User::query()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'password' => ['required', 'confirmed', $this->passwordRule()],
        ]);

        User::create($data);

        return back()->with('success', 'Akun CMS berhasil dibuat.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
        ]);

        if ($user->isHeadAdmin() && $data['role'] !== User::ROLE_HEAD_ADMIN) {
            $this->ensureAnotherHeadAdmin($user);
        }

        $user->update($data);

        return back()->with('success', 'Akun CMS berhasil diperbarui.');
    }

    public function password(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', $this->passwordRule()],
        ]);

        $user->forceFill([
            'password' => Hash::make($data['password']),
            'remember_token' => null,
        ])->save();

        return back()->with('success', 'Kata sandi '.$user->name.' berhasil diganti.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors(['user' => 'Akun yang sedang digunakan tidak dapat dihapus.']);
        }

        if ($user->isHeadAdmin()) {
            $this->ensureAnotherHeadAdmin($user);
        }

        $user->delete();

        return back()->with('success', 'Akun CMS berhasil dihapus.');
    }

    private function ensureAnotherHeadAdmin(User $user): void
    {
        if (User::query()->where('role', User::ROLE_HEAD_ADMIN)->whereKeyNot($user->getKey())->doesntExist()) {
            throw ValidationException::withMessages([
                'role' => 'Minimal satu Ketua Admin harus tetap tersedia.',
            ]);
        }
    }

    private function passwordRule(): Password
    {
        return Password::min(8)->mixedCase()->numbers();
    }
}
