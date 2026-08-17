@extends('admin.layouts.app')
@section('title','Kelola Pengguna')
@section('header','Kelola pengguna CMS')
@section('content')
<div class="page-head">
  <div><h1>Pengguna & hak akses</h1><p>Tambah akun, tentukan role, atau atur ulang kata sandi pengguna CMS.</p></div>
</div>

<div class="user-management-grid">
  <form class="admin-form" method="post" action="{{ route('admin.users.store') }}">
    @csrf
    <section class="admin-card">
      <div class="form-section-title"><span>＋</span><div><h3>Tambah pengguna</h3><p>Buat akses baru untuk anggota tim.</p></div></div>
      <div class="fields">
        <label>Nama<input name="name" value="{{ old('name') }}" required maxlength="100"></label>
        <label>Email<input type="email" name="email" value="{{ old('email') }}" required></label>
        <label class="wide">Role
          <select name="role" required>
            @foreach(\App\Models\User::roles() as $value => $label)<option value="{{ $value }}" @selected(old('role')===$value)>{{ $label }}</option>@endforeach
          </select>
          <small>Ketua Admin: semua akses. Tim Admin: isi web. Kontributor: berita dan event.</small>
        </label>
        <label>Kata sandi awal<input type="password" name="password" required autocomplete="new-password"></label>
        <label>Ulangi kata sandi<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
      </div>
      <button class="primary-button user-create-button" type="submit">Buat Akun</button>
    </section>
  </form>

  <section class="user-list">
    @foreach($users as $user)
      <article class="admin-card user-card">
        <div class="user-card-head"><span>{{ strtoupper(substr($user->name,0,1)) }}</span><div><h3>{{ $user->name }}</h3><p>{{ $user->email }}</p></div><em>{{ $user->roleLabel() }}</em></div>
        <form class="fields user-update-form" method="post" action="{{ route('admin.users.update',$user) }}">
          @csrf @method('PUT')
          <label>Nama<input name="name" value="{{ $user->name }}" required maxlength="100"></label>
          <label>Email<input type="email" name="email" value="{{ $user->email }}" required></label>
          <label class="wide">Role<select name="role" required>@foreach(\App\Models\User::roles() as $value => $label)<option value="{{ $value }}" @selected($user->role===$value)>{{ $label }}</option>@endforeach</select></label>
          <button class="secondary-button" type="submit">Simpan Data & Role</button>
        </form>
        <form class="fields user-password-form" method="post" action="{{ route('admin.users.password',$user) }}">
          @csrf @method('PUT')
          <label>Kata sandi baru<input type="password" name="password" required autocomplete="new-password"></label>
          <label>Ulangi kata sandi<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
          <button class="secondary-button" type="submit">Ganti Kata Sandi</button>
        </form>
        @unless(auth()->user()->is($user))
          <form method="post" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('Hapus akses CMS untuk {{ addslashes($user->name) }}?')">
            @csrf @method('DELETE')
            <button class="danger-button" type="submit">Hapus Pengguna</button>
          </form>
        @endunless
      </article>
    @endforeach
  </section>
</div>
@endsection
