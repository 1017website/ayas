@extends('admin.layouts.app')
@section('title','Pengaturan Website')
@section('header','Pengaturan website')
@section('content')
<div class="page-head"><div><h1>Editor company profile</h1><p>Seluruh konten frontend dikelola di sini tanpa mengubah desain aslinya.</p></div><a class="secondary-button" href="{{ route('home') }}" target="_blank">↗ Preview website</a></div>

<div class="content-editor-help"><span>Tips</span><p>Isi kolom <b>English</b> dan <b>Indonesia</b>. Teks bertanda “mendukung italic” dapat memakai tag <code>&lt;em&gt;teks&lt;/em&gt;</code>. Pada copyright footer, gunakan <code>{year}</code> agar tahun diperbarui otomatis.</p></div>

<form class="admin-form settings-form" method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">@csrf @method('PUT')
  <nav class="section-jump settings-tabs" role="tablist" aria-label="Kelompok pengaturan website">
    <a href="#media" role="tab" data-settings-tab="media" aria-controls="media">Media</a><a href="#detail" role="tab" data-settings-tab="detail" aria-controls="detail">Informasi Umum</a><a href="#seo" role="tab" data-settings-tab="seo" aria-controls="seo">SEO & Meta</a>@if(auth()->user()->isHeadAdmin())<a href="#tracking" role="tab" data-settings-tab="tracking" aria-controls="tracking">Meta & Google Ads</a><a href="#qontak" role="tab" data-settings-tab="qontak" aria-controls="qontak">Mekari Qontak</a>@endif
    @foreach($contentSections as $section => $fields)@php($tabId='section-'.Str::slug($section))<a href="#{{ $tabId }}" role="tab" data-settings-tab="{{ $tabId }}" aria-controls="{{ $tabId }}">{{ $section }}</a>@endforeach
  </nav>

  <section class="admin-card" id="media" role="tabpanel" data-settings-panel><div class="form-section-title"><span>◫</span><div><h3>Media & gambar</h3><p>Gambar default sudah sama dengan file desain. Upload hanya jika ingin menggantinya.</p></div></div>
    <div class="media-settings-grid">@foreach($mediaFields as $key => $field)
      @php($current=$settings['media_'.$key]??$field['default'])
      @php($preview=str_starts_with($current,'http')?$current:(str_starts_with($current,'assets/')?asset($current):asset('storage/'.$current)))
      <label class="media-setting"><img src="{{ $preview }}" alt=""><span><b>{{ $field['label'] }}</b><small>Klik untuk mengganti · maks. 5 MB</small></span><input type="file" name="media[{{ $key }}]" accept="image/*"></label>
    @endforeach</div>
  </section>

  @php($detailLabels=['company_name'=>'Nama Perusahaan','address'=>'Alamat','phone'=>'Nomor yang Ditampilkan','whatsapp'=>'Nomor WhatsApp (format 62)','email'=>'Email','instagram'=>'Username Instagram','instagram_url'=>'URL Instagram','website'=>'Alamat Website','meta_title'=>'Judul SEO','meta_description'=>'Deskripsi SEO','stat_1_value'=>'Nilai Statistik Hero 1','stat_2_value'=>'Nilai Statistik Hero 2','stat_3_value'=>'Nilai Statistik Hero 3','metric_1_value'=>'Nilai Metrik Kapabilitas 1','metric_2_value'=>'Nilai Metrik Kapabilitas 2','metric_3_value'=>'Nilai Metrik Kapabilitas 3','metric_4_value'=>'Nilai Metrik Kapabilitas 4'])
  <section class="admin-card" id="detail" role="tabpanel" data-settings-panel><div class="form-section-title"><span>01</span><div><h3>Informasi umum & statistik</h3><p>Identitas, kontak, SEO, dan angka yang tampil pada website.</p></div></div><div class="fields">
    @foreach($detailFields as $key => $default)<label @class(['wide'=>in_array($key,['address','meta_title','meta_description'])])>{{ $detailLabels[$key]??Str::headline($key) }}
      @if(in_array($key,['address','meta_description']))<textarea name="details[{{ $key }}]" rows="3" required>{{ old('details.'.$key,$settings[$key]??$default) }}</textarea>@else<input name="details[{{ $key }}]" value="{{ old('details.'.$key,$settings[$key]??$default) }}" required>@endif
    </label>@endforeach
  </div></section>

  <section class="admin-card" id="seo" role="tabpanel" data-settings-panel><div class="form-section-title"><span>SEO</span><div><h3>SEO & meta sosial</h3><p>Atur canonical, indeks Google, dan tampilan link saat dibagikan ke WhatsApp atau media sosial.</p></div></div><div class="fields">
    @foreach($seoFields as $key => $field)<label @class(['wide'=>($field['textarea']??false)])>{{ $field['label'] }}
      @if($field['textarea']??false)<textarea name="seo[{{ $key }}]" rows="3">{{ old('seo.'.$key,$settings[$key]??$field['default']) }}</textarea>@else<input name="seo[{{ $key }}]" value="{{ old('seo.'.$key,$settings[$key]??$field['default']) }}">@endif
      @if($field['help']??false)<small>{{ $field['help'] }}</small>@endif
    </label>@endforeach
  </div></section>

  @if(auth()->user()->isHeadAdmin())
  <section class="admin-card" id="tracking" role="tabpanel" data-settings-panel><div class="form-section-title"><span>ADS</span><div><h3>Meta Pixel, Google Analytics, Tag Manager & Ads</h3><p>Kosongkan layanan yang tidak digunakan. Event prospek otomatis dikirim ketika formulir WhatsApp atau email digunakan.</p></div></div><div class="fields">
    @foreach($trackingFields as $key => $field)<label>{{ $field['label'] }}<input name="tracking[{{ $key }}]" value="{{ old('tracking.'.$key,$settings[$key]??$field['default']) }}" placeholder="{{ $field['placeholder']??'' }}">@if($field['help']??false)<small>{{ $field['help'] }}</small>@endif</label>@endforeach
  </div><div class="content-editor-help tracking-help"><span>Info</span><p>Statistik internal CMS tetap berjalan meski ID Google belum diisi. Jika memakai GTM, konfigurasi tag di akun Tag Manager Anda.</p></div></section>

  <section class="admin-card" id="qontak" role="tabpanel" data-settings-panel><div class="form-section-title"><span>WA</span><div><h3>Mekari Qontak WhatsApp</h3><p>Integrasi sudah disiapkan dan tidak akan mengirim pesan sebelum diaktifkan serta kredensial lengkap.</p></div></div>
    <div class="integration-status {{ $qontakReady?'ready':'pending' }}"><b>{{ $qontakReady?'Siap digunakan':'Belum dikonfigurasi' }}</b><span>{{ $qontakReady?'Pesan formulir baru akan masuk antrean Qontak.':'Lengkapi token, channel, dan template lalu aktifkan integrasi.' }}</span></div>
    <div class="fields qontak-fields">@foreach($qontakFields as $key => $field)
      @if(($field['type']??null)==='checkbox')<label class="switch-row wide"><span><b>{{ $field['label'] }}</b><small>Aktifkan setelah template WhatsApp disetujui dan kredensial sudah benar.</small></span><input type="checkbox" name="qontak[{{ $key }}]" value="1" @checked(old('qontak.'.$key,$settings[$key]??$field['default'])==='1')><i></i></label>
      @else<label @class(['wide'=>in_array($key,['qontak_base_url'])])>{{ $field['label'] }}<input type="{{ ($field['type']??'text')==='password'?'password':'text' }}" name="qontak[{{ $key }}]" value="{{ ($field['secret']??false)?'':old('qontak.'.$key,$settings[$key]??$field['default']) }}" placeholder="{{ ($field['secret']??false)&&isset($settings[$key])?'Tersimpan — isi hanya untuk mengganti':($field['placeholder']??'') }}">@if($field['help']??false)<small>{{ $field['help'] }}</small>@endif</label>@endif
    @endforeach</div>
    <div class="webhook-box"><b>Webhook penerimaan event</b><code>{{ route('webhooks.qontak') }}?secret=WEBHOOK_SECRET_ANDA</code><small>Daftarkan URL ini di Mekari Qontak dan ganti bagian WEBHOOK_SECRET_ANDA dengan secret yang sama.</small></div>
  </section>
  @endif

  @foreach($contentSections as $section => $fields)
    <section class="admin-card bilingual-section" id="section-{{ Str::slug($section) }}" role="tabpanel" data-settings-panel><div class="form-section-title"><span>{{ str_pad($loop->iteration+1,2,'0',STR_PAD_LEFT) }}</span><div><h3>{{ $section }}</h3><p>Edit kedua bahasa. Perubahan langsung mengikuti tombol ID/EN di frontend.</p></div></div>
      <div class="bilingual-head"><span>Nama Konten</span><b>EN · English</b><b>ID · Indonesia</b></div>
      <div class="bilingual-fields">@foreach($fields as $key => $field)
        <div class="bilingual-row"><label><b>{{ $field['label'] }}</b><small>{{ $key }} @if($field['html']??false) · mendukung italic @endif</small></label>
          @foreach(['en'=>'English','id'=>'Indonesia'] as $language => $languageLabel)
            @php($value=old('content.'.$key.'.'.$language,$settings['content_'.$key.'_'.$language]??$field[$language]))
            @if(($field['textarea']??false)||($field['html']??false))<textarea name="content[{{ $key }}][{{ $language }}]" rows="{{ $field['textarea']??false?3:2 }}" required>{{ $value }}</textarea>@else<input name="content[{{ $key }}][{{ $language }}]" value="{{ $value }}" required>@endif
          @endforeach
        </div>
      @endforeach</div>
    </section>
  @endforeach

  <div class="sticky-save"><p><b>Siap menyimpan perubahan?</b><span>Konten akan langsung mengikuti desain frontend asli.</span></p><button class="primary-button" type="submit">Simpan Semua Perubahan</button></div>
</form>

<script>
(() => {
  const form = document.querySelector('.settings-form');
  const tabs = [...document.querySelectorAll('[data-settings-tab]')];
  const panels = [...document.querySelectorAll('[data-settings-panel]')];
  if (!form || !tabs.length || !panels.length) return;

  const available = new Set(panels.map(panel => panel.id));
  const fallback = 'media';
  const errorTab = @json($errors->any() ? Str::before($errors->keys()[0], '.') : null);
  const resolveInitialTab = () => {
    const hash = decodeURIComponent(window.location.hash.replace('#', ''));
    if (available.has(hash)) return hash;
    if (errorTab === 'details') return 'detail';
    if (errorTab === 'content') return 'section-navigasi-tombol';
    if (available.has(errorTab)) return errorTab;
    return fallback;
  };

  const activate = (id, updateUrl = false) => {
    if (!available.has(id)) id = fallback;
    panels.forEach(panel => {
      const active = panel.id === id;
      panel.classList.toggle('is-active', active);
      panel.setAttribute('aria-hidden', String(!active));
    });
    tabs.forEach(tab => {
      const active = tab.dataset.settingsTab === id;
      tab.classList.toggle('is-active', active);
      tab.setAttribute('aria-selected', String(active));
      tab.tabIndex = active ? 0 : -1;
    });
    if (updateUrl) history.replaceState(null, '', `#${id}`);
  };

  form.classList.add('tabs-ready');
  activate(resolveInitialTab());
  tabs.forEach(tab => tab.addEventListener('click', event => {
    event.preventDefault();
    activate(tab.dataset.settingsTab, true);
  }));
  window.addEventListener('hashchange', () => activate(resolveInitialTab()));

  let openedInvalidTab = false;
  form.addEventListener('invalid', event => {
    if (openedInvalidTab) return;
    const panel = event.target.closest('[data-settings-panel]');
    if (panel) {
      openedInvalidTab = true;
      activate(panel.id, true);
      setTimeout(() => event.target.focus(), 0);
    }
  }, true);
  form.addEventListener('input', () => { openedInvalidTab = false; });
})();
</script>
@endsection
