@extends('admin.layouts.app')
@section('title','Akun CMS')
@section('header','Akun CMS')
@section('content')
<div class="page-head">
  <div><h1>Keamanan akun</h1><p>Kelola kata sandi untuk akun CMS yang sedang digunakan.</p></div>
</div>

<div class="account-grid">
  <form class="admin-form password-form" method="post" action="{{ route('admin.profile.password') }}">
    @csrf
    @method('PUT')
    <section class="admin-card">
      <div class="form-section-title"><span>⌁</span><div><h3>Ganti kata sandi</h3><p>Setelah tersimpan, gunakan kata sandi baru pada login berikutnya.</p></div></div>
      <div class="account-identity"><span>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span><p><b>{{ auth()->user()->name }}</b><small>{{ auth()->user()->email }}</small></p></div>
      <div class="fields password-fields">
        <label class="wide">Kata sandi saat ini
          <input type="password" name="current_password" autocomplete="current-password" required autofocus @class(['is-invalid'=>$errors->has('current_password')])>
          @error('current_password')<small class="field-error">{{ $message }}</small>@enderror
        </label>
        <label>Kata sandi baru
          <input type="password" name="password" autocomplete="new-password" required @class(['is-invalid'=>$errors->has('password')])>
          <small>Minimal 8 karakter, terdiri dari huruf besar, huruf kecil, dan angka.</small>
          @error('password')<small class="field-error">{{ $message }}</small>@enderror
        </label>
        <label>Ulangi kata sandi baru
          <input type="password" name="password_confirmation" autocomplete="new-password" required>
        </label>
      </div>
      <button class="primary-button password-button" type="submit">Simpan Kata Sandi Baru</button>
    </section>
  </form>
  <aside class="admin-card account-note"><span>i</span><div><h3>Tips keamanan</h3><p>Gunakan kata sandi yang unik dan tidak digunakan pada layanan lain. Perubahan hanya berlaku untuk akun yang tampil di sebelah kiri.</p></div></aside>
</div>
@endsection
