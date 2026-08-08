@extends('admin.layouts.app')
@section('title',$post->exists?'Edit Berita':'Buat Berita')
@section('header',$post->exists?'Edit berita':'Buat berita')
@section('content')
<div class="page-head"><div><a class="back-link" href="{{ route('admin.berita.index') }}">← Kembali ke berita</a><h1>{{ $post->exists?'Perbarui berita':'Tulis cerita baru' }}</h1><p>Seluruh isi halaman detail tersedia dalam English dan Indonesia.</p></div></div>
<form class="editor-layout" method="post" enctype="multipart/form-data" action="{{ $post->exists?route('admin.berita.update',$post):route('admin.berita.store') }}">@csrf @if($post->exists)@method('PUT')@endif
  <div class="editor-main">
    <section class="admin-card"><div class="form-section-title"><span>01</span><div><h3>Isi berita bilingual</h3><p>Judul, kategori, ringkasan, dan isi utama halaman.</p></div></div><div class="bilingual-head"><span>Konten</span><b>EN · English</b><b>ID · Indonesia</b></div><div class="bilingual-fields">
      @foreach([['title','title_id','Judul',false,2],['category','category_id','Kategori',false,2],['excerpt','excerpt_id','Ringkasan',true,3],['body','body_id','Isi berita',true,12]] as [$en,$id,$label,$area,$rows])<div class="bilingual-row"><label><b>{{ $label }}</b><small>{{ $en }}</small></label>@if($area)<textarea name="{{ $en }}" rows="{{ $rows }}" required>{{ old($en,$post->$en) }}</textarea><textarea name="{{ $id }}" rows="{{ $rows }}" required>{{ old($id,$post->$id) }}</textarea>@else<input name="{{ $en }}" value="{{ old($en,$post->$en) }}" required><input name="{{ $id }}" value="{{ old($id,$post->$id) }}" required>@endif</div>@endforeach
    </div><div class="fields product-extra"><label>Tanggal tayang<input type="datetime-local" name="published_at" value="{{ old('published_at',$post->published_at?->format('Y-m-d\TH:i')) }}"></label></div></section>

    <section class="admin-card"><div class="form-section-title"><span>02</span><div><h3>Caption galeri bilingual</h3><p>Tiga caption ini tampil di galeri dan lightbox berita.</p></div></div><div class="bilingual-head"><span>Foto</span><b>EN · English</b><b>ID · Indonesia</b></div><div class="bilingual-fields">
      @foreach(range(1,3) as $number)@php $en='gallery_caption_'.$number; $id=$en.'_id'; @endphp<div class="bilingual-row"><label><b>Caption foto {{ $number }}</b><small>{{ $en }}</small></label><input name="{{ $en }}" value="{{ old($en,$post->$en) }}"><input name="{{ $id }}" value="{{ old($id,$post->$id) }}"></div>@endforeach
    </div></section>

    <section class="admin-card"><div class="form-section-title"><span>SEO</span><div><h3>SEO khusus berita</h3><p>Opsional. Jika kosong, judul dan ringkasan berita dipakai otomatis.</p></div></div><div class="fields"><label class="wide">Judul SEO<input name="seo_title" value="{{ old('seo_title',$post->seo_title) }}" maxlength="200"></label><label class="wide">Deskripsi SEO<textarea name="seo_description" rows="3" maxlength="500">{{ old('seo_description',$post->seo_description) }}</textarea></label></div></section>
  </div>

  <aside class="editor-side">
    <section class="admin-card"><h3>Publikasi</h3><label class="switch-row"><span><b>Terbitkan berita</b><small>Berita dapat dibaca publik</small></span><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$post->is_published))><i></i></label></section>
    @foreach(['image'=>'Gambar utama','gallery_image_2'=>'Gambar galeri 2','gallery_image_3'=>'Gambar galeri 3'] as $field=>$label)
      <section class="admin-card"><h3>{{ $label }}</h3>@php $current=$post->$field; $image=$current?(str_starts_with($current,'assets/')?asset($current):asset('storage/'.$current)):null; @endphp<label class="upload-box"><input type="file" name="{{ $field }}" accept="image/*" onchange="this.nextElementSibling.src=URL.createObjectURL(this.files[0]);this.nextElementSibling.hidden=false"><img src="{{ $image }}" @if(!$image) hidden @endif alt="Preview"><span>＋</span><b>Pilih gambar</b><small>Disarankan rasio 16:9</small></label></section>
    @endforeach
    <button class="primary-button full-button" type="submit">{{ $post->exists?'Simpan Perubahan':'Simpan Berita' }}</button>
  </aside>
</form>
@endsection
