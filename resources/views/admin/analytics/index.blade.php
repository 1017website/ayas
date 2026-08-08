@extends('admin.layouts.app')
@section('title','Statistik Pengunjung')
@section('header','Statistik pengunjung')
@section('content')
<div class="page-head"><div><h1>Performa website</h1><p>Statistik internal yang ringan, tanpa menyimpan alamat IP asli pengunjung.</p></div><small>Diperbarui {{ $generatedAt->translatedFormat('d M Y, H:i') }}</small></div>

<div class="stat-grid analytics-summary">
  <article><i class="blue">↗</i><div><span>Page View Hari Ini</span><b>{{ number_format($summary['today']) }}</b><small>Kunjungan halaman hari ini</small></div></article>
  <article><i class="gold">◉</i><div><span>Page View 30 Hari</span><b>{{ number_format($summary['views30']) }}</b><small>Total halaman dilihat</small></div></article>
  <article><i class="purple">◎</i><div><span>Pengunjung Unik</span><b>{{ number_format($summary['visitors30']) }}</b><small>30 hari terakhir</small></div></article>
  <article><i class="green">✉</i><div><span>Prospek Masuk</span><b>{{ number_format($summary['leads30']) }}</b><small>Formulir 30 hari terakhir</small></div></article>
</div>

<section class="admin-card analytics-chart-card"><div class="card-head"><div><h3>Tren 30 hari</h3><p>Page view dan pengunjung unik per hari</p></div></div><div class="analytics-chart">
  @foreach($chart as $point)<div class="chart-column" title="{{ $point['label'] }}: {{ $point['views'] }} view, {{ $point['visitors'] }} pengunjung"><div class="chart-bars"><i style="height:{{ max(3,($point['views']/$maxViews)*100) }}%"></i><b style="height:{{ max(3,($point['visitors']/$maxViews)*100) }}%"></b></div>@if($loop->first || $loop->iteration % 5 === 0 || $loop->last)<span>{{ $point['label'] }}</span>@else<span></span>@endif</div>@endforeach
</div><div class="chart-legend"><span><i></i> Page view</span><span><i></i> Pengunjung unik</span></div></section>

<div class="analytics-grid">
  <section class="admin-card"><div class="card-head"><div><h3>Halaman teratas</h3><p>30 hari terakhir</p></div></div><div class="rank-list">@forelse($topPages as $path=>$count)<div><b>{{ $path }}</b><span>{{ number_format($count) }} view</span></div>@empty<div class="empty-state">Belum ada data kunjungan.</div>@endforelse</div></section>
  <section class="admin-card"><div class="card-head"><div><h3>Sumber kunjungan</h3><p>UTM atau website perujuk</p></div></div><div class="rank-list">@forelse($sources as $source=>$count)<div><b>{{ $source }}</b><span>{{ number_format($count) }}</span></div>@empty<div class="empty-state">Belum ada data sumber.</div>@endforelse</div></section>
  <section class="admin-card"><div class="card-head"><div><h3>Perangkat</h3><p>Desktop, mobile, dan tablet</p></div></div><div class="rank-list">@forelse($devices as $device=>$count)<div><b>{{ $device }}</b><span>{{ number_format($count) }}</span></div>@empty<div class="empty-state">Belum ada data perangkat.</div>@endforelse</div></section>
</div>
@endsection
