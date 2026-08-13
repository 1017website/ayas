@extends('admin.layouts.app')
@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')
@section('header', $product->exists ? 'Edit produk' : 'Tambah produk')

@section('content')
<div class="page-head">
    <div>
        <a class="back-link" href="{{ route('admin.produk.index') }}">← Kembali ke produk</a>
        <h1>{{ $product->exists ? 'Perbarui produk' : 'Produk baru' }}</h1>
        <p>Konten bilingual mengikuti tombol ID/EN pada frontend.</p>
    </div>
</div>

<form class="editor-layout" method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.produk.update', $product) : route('admin.produk.store') }}">
    @csrf
    @if($product->exists) @method('PUT') @endif

    <div class="editor-main product-editor-main">
        <section class="admin-card">
            <div class="form-section-title">
                <span>01</span>
                <div><h3>Informasi produk</h3><p>Isi konten English dan Indonesia sesuai desain.</p></div>
            </div>
            <div class="bilingual-head"><span>Konten</span><b>EN · English</b><b>ID · Indonesia</b></div>
            <div class="bilingual-fields">
                @foreach([
                    ['name','name_id','Nama produk',false],
                    ['market','market_id','Label target pasar',false],
                    ['short_description','short_description_id','Deskripsi kartu',true],
                    ['description','description_id','Deskripsi modal',true]
                ] as [$en,$id,$label,$area])
                    <div class="bilingual-row">
                        <label><b>{{ $label }}</b><small>{{ $en }}</small></label>
                        @if($area)
                            <textarea name="{{ $en }}" rows="3" required>{{ old($en, $product->$en) }}</textarea>
                            <textarea name="{{ $id }}" rows="3" required>{{ old($id, $product->$id) }}</textarea>
                        @else
                            <input name="{{ $en }}" value="{{ old($en, $product->$en) }}" required>
                            <input name="{{ $id }}" value="{{ old($id, $product->$id) }}" required>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="fields product-extra">
                <label>Urutan tampil<input type="number" min="0" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" required></label>
                <label>URL gambar utama (opsional)<input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://images.unsplash.com/..."><small>URL ini dipakai lebih dahulu agar sama seperti desain asli.</small></label>
            </div>
        </section>

        @php
            $resolveImage = fn ($path) => $path ? (str_starts_with($path, 'assets/') ? asset($path) : asset('storage/'.$path)) : null;
            $productImages = [
                'image' => ['Foto utama / fallback', 'Dipakai di kartu produk jika URL utama gagal'],
                'gallery_image' => ['Foto galeri 2', 'Tampil di modal produk'],
                'gallery_image_3' => ['Foto galeri 3', 'Tampil di modal produk'],
                'gallery_image_4' => ['Foto galeri 4', 'Tampil di modal produk'],
            ];
        @endphp
        <section class="admin-card product-media-card">
            <div class="form-section-title">
                <span>02</span>
                <div><h3>Media produk</h3><p>Semua foto diletakkan sejajar agar lebih cepat dilihat dan diperbarui.</p></div>
            </div>
            <div class="product-media-grid">
                @foreach($productImages as $field => [$label, $help])
                    @php($preview = $resolveImage($product->$field))
                    <div class="product-media-item">
                        <strong>{{ $label }}</strong>
                        <label class="upload-box">
                            <input type="file" name="{{ $field }}" accept="image/*" onchange="this.nextElementSibling.src=URL.createObjectURL(this.files[0]);this.nextElementSibling.hidden=false">
                            <img src="{{ $preview }}" @if(!$preview) hidden @endif alt="Preview {{ strtolower($label) }}">
                            <span>＋</span><b>Pilih foto</b><small>{{ $help }}</small>
                        </label>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <aside class="editor-side product-editor-side">
        <section class="admin-card">
            <h3>Status tayang</h3>
            <label class="switch-row"><span><b>Aktifkan produk</b><small>Produk terlihat di website</small></span><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))><i></i></label>
        </section>
        <button class="primary-button full-button" type="submit">{{ $product->exists ? 'Simpan Perubahan' : 'Tambahkan Produk' }}</button>
    </aside>
</form>
@endsection
