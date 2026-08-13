@extends('admin.layouts.app')
@section('title', 'Developer Tools')
@section('header', 'Developer tools')

@section('content')
<div class="page-head developer-head">
    <div><h1>Developer tools</h1><p>Perawatan aplikasi yang hanya tersedia untuk akun developer.</p></div>
    <span class="developer-badge">Akses terbatas</span>
</div>

<div class="developer-tools-grid">
    @foreach([
        ['migrate', '⇧', 'Migrate', 'Menjalankan migrasi database yang belum diterapkan.', 'Jalankan migrate'],
        ['optimize-clear', '↻', 'Optimize clear', 'Membersihkan cache konfigurasi, route, view, dan event.', 'Bersihkan cache'],
        ['storage-link', '↗', 'Storage link', $storageLinked ? 'Tautan public/storage sudah tersedia.' : 'Membuat tautan public/storage untuk file unggahan.', $storageLinked ? 'Buat ulang link' : 'Buat storage link'],
    ] as [$tool, $icon, $title, $description, $button])
        <article class="admin-card developer-tool-card">
            <span class="developer-tool-icon">{{ $icon }}</span>
            <div><h2>{{ $title }}</h2><p>{{ $description }}</p></div>
            <form method="post" action="{{ route('admin.developer.run') }}" onsubmit="return confirm('Jalankan {{ $title }} sekarang?')">
                @csrf
                <input type="hidden" name="tool" value="{{ $tool }}">
                <button class="{{ $tool === 'migrate' ? 'primary-button' : 'secondary-button' }}" type="submit">{{ $button }}</button>
            </form>
        </article>
    @endforeach
</div>

<section class="admin-card developer-note">
    <span>!</span><div><h3>Catatan keamanan</h3><p>Gunakan satu perintah dalam satu waktu. Semua aksi dijalankan di lingkungan aplikasi aktif dan dilindungi oleh login, pemeriksaan akun developer, CSRF, pembatasan request, serta process lock.</p></div>
</section>
@endsection
