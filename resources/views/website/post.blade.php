<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('website.partials.tracking-head')
  @include('website.partials.seo')
  <meta name="theme-color" content="#071f3d">
  <link rel="icon" type="image/png" href="{{ $media['favicon'] }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">
  <style>
    :root { --navy:#071f3d; --navy-2:#0b2e57; --gold:#c89543; --gold-light:#e7c17d; --paper:#fbf9f4; --ivory:#f5f0e7; --ink:#0c2442; --muted:#6f7882; --line:rgba(7,31,61,.14); --ease:cubic-bezier(.2,.75,.2,1); }
    * { box-sizing:border-box; }
    html { scroll-behavior:smooth; }
    body { margin:0; color:var(--ink); background:var(--paper); font-family:"DM Sans",sans-serif; }
    img { display:block; max-width:100%; }
    a { color:inherit; text-decoration:none; }
    button { border:0; font:inherit; }
    body.lightbox-open { overflow:hidden; }
    .container { width:min(1120px,calc(100% - 48px)); margin:auto; }
    .header { position:absolute; inset:0 0 auto; z-index:10; }
    .nav { height:92px; display:flex; align-items:center; justify-content:space-between; gap:24px; }
    .brand img { width:174px; }
    .nav__right { display:flex; align-items:center; gap:22px; }
    .back { color:rgba(255,255,255,.78); font-size:12px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; }
    .back:hover { color:var(--gold-light); }
    .language-switch { display:inline-flex; gap:3px; padding:3px; border:1px solid rgba(255,255,255,.22); border-radius:999px; background:rgba(255,255,255,.08); }
    .language-switch button { min-width:39px; height:31px; padding:0 9px; border-radius:999px; color:rgba(255,255,255,.66); background:transparent; font-size:11px; font-weight:700; letter-spacing:.08em; cursor:pointer; }
    .language-switch button.is-active { color:var(--navy); background:var(--gold-light); }
    .hero { min-height:610px; padding:175px 0 90px; color:white; background:linear-gradient(100deg,rgba(7,31,61,.98) 0%,rgba(7,31,61,.83) 57%,rgba(7,31,61,.35)),var(--hero-image) center/cover; }
    .eyebrow { display:flex; align-items:center; gap:12px; color:var(--gold-light); font-size:11px; font-weight:700; letter-spacing:.18em; text-transform:uppercase; }
    .eyebrow::before { content:""; width:38px; height:1px; background:currentColor; }
    h1 { max-width:800px; margin:28px 0 25px; font-family:"Playfair Display",serif; font-size:clamp(54px,7vw,94px); font-weight:500; letter-spacing:-.04em; line-height:.96; }
    .hero__lead { max-width:680px; margin:0; color:rgba(255,255,255,.72); font-size:18px; line-height:1.75; }
    .visual-story { position:relative; z-index:3; margin-top:-68px; }
    .visual-story__grid { display:grid; grid-template-columns:1.55fr .8fr; grid-template-rows:250px 250px; gap:18px; padding:18px; border:1px solid rgba(255,255,255,.66); border-radius:30px; background:rgba(251,249,244,.88); box-shadow:0 28px 80px rgba(7,31,61,.2); backdrop-filter:blur(14px); }
    .story-image { position:relative; margin:0; padding:0; overflow:hidden; border-radius:20px; color:white; background:#d5c7b3; text-align:left; cursor:zoom-in; }
    .story-image--main { grid-row:1/-1; }
    .story-image img { width:100%; height:100%; object-fit:cover; transition:transform .8s var(--ease); }
    .story-image:hover img { transform:scale(1.045); }
    .story-image::after { content:""; position:absolute; inset:45% 0 0; background:linear-gradient(transparent,rgba(4,20,39,.82)); pointer-events:none; }
    .story-image__caption { position:absolute; z-index:2; left:22px; right:22px; bottom:19px; color:white; font-size:12px; font-weight:600; letter-spacing:.04em; }
    .story-image--main .story-image__caption { font-family:"Playfair Display",serif; font-size:23px; font-weight:500; }
    .story-image__zoom { position:absolute; z-index:3; right:16px; top:16px; display:grid; place-items:center; width:38px; height:38px; border-radius:50%; color:white; background:rgba(7,31,61,.72); font-size:18px; opacity:0; transform:translateY(-5px); transition:.3s var(--ease); }
    .story-image:hover .story-image__zoom,.story-image:focus-visible .story-image__zoom { opacity:1; transform:none; }
    .story-image:focus-visible { outline:3px solid var(--gold); outline-offset:4px; }
    .article { display:grid; grid-template-columns:minmax(0,1fr) 290px; gap:90px; padding:95px 0 110px; }
    .article__body { max-width:720px; }
    .article__body #articleParagraphs p { margin:0 0 28px; color:#4f5d6c; font-size:18px; line-height:1.9; }
    .article__body h2 { margin:58px 0 22px; font-family:"Playfair Display",serif; font-size:40px; font-weight:500; }
    .article__body ul { display:grid; gap:14px; margin:0; padding:0; list-style:none; }
    .article__body li { position:relative; padding:18px 20px 18px 48px; border:1px solid var(--line); border-radius:15px; background:white; line-height:1.65; }
    .article__body li::before { content:""; position:absolute; left:20px; top:26px; width:9px; height:9px; border-radius:50%; background:var(--gold); }
    .article__note { margin-top:48px; padding:24px 28px; border-left:3px solid var(--gold); color:var(--muted); background:var(--ivory); line-height:1.7; }
    .sidebar { align-self:start; position:sticky; top:30px; }
    .sidebar__label { margin-bottom:16px; color:var(--gold); font-size:10px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; }
    .related { display:grid; gap:12px; }
    .related a { padding:19px; border:1px solid var(--line); border-radius:15px; background:white; transition:transform .3s var(--ease),border-color .3s; }
    .related a:hover,.related a.is-active { transform:translateX(5px); border-color:var(--gold); }
    .related strong { display:block; margin-bottom:5px; font-family:"Playfair Display",serif; font-size:20px; }
    .related span { color:var(--muted); font-size:11px; }
    .cta { padding:70px 0; color:white; background:var(--navy); }
    .cta__inner { display:flex; justify-content:space-between; align-items:center; gap:40px; }
    .cta h2 { max-width:680px; margin:0; font-family:"Playfair Display",serif; font-size:clamp(38px,5vw,60px); font-weight:500; line-height:1.05; }
    .btn { display:inline-flex; align-items:center; justify-content:center; min-height:54px; padding:0 24px; border-radius:999px; color:var(--navy); background:var(--gold); font-size:12px; font-weight:700; letter-spacing:.09em; text-transform:uppercase; white-space:nowrap; }
    .image-lightbox { position:fixed; inset:0; z-index:100; display:grid; place-items:center; padding:32px; background:rgba(2,13,27,.92); backdrop-filter:blur(12px); }
    .image-lightbox[hidden] { display:none; }
    .image-lightbox__dialog { position:relative; width:min(1120px,100%); }
    .image-lightbox__image-wrap { display:grid; place-items:center; height:min(76vh,760px); overflow:hidden; border-radius:24px; background:#06182e; box-shadow:0 35px 100px rgba(0,0,0,.48); }
    .image-lightbox img { width:100%; height:100%; object-fit:contain; }
    .image-lightbox__caption { margin:16px 70px 0; color:rgba(255,255,255,.78); text-align:center; font-size:14px; }
    .image-lightbox__close,.image-lightbox__nav { position:absolute; z-index:2; display:grid; place-items:center; border-radius:50%; color:white; background:rgba(7,31,61,.82); cursor:pointer; transition:background .25s,color .25s; }
    .image-lightbox__close:hover,.image-lightbox__nav:hover { color:var(--navy); background:var(--gold-light); }
    .image-lightbox__close { top:16px; right:16px; width:46px; height:46px; font-size:25px; }
    .image-lightbox__nav { top:50%; width:48px; height:48px; font-size:23px; transform:translateY(-50%); }
    .image-lightbox__prev { left:16px; }
    .image-lightbox__next { right:16px; }
    .image-lightbox__count { position:absolute; left:20px; top:20px; z-index:2; padding:8px 12px; border-radius:999px; color:white; background:rgba(7,31,61,.76); font-size:11px; font-weight:700; letter-spacing:.1em; }
    footer { padding:28px 0; color:rgba(255,255,255,.5); background:#05182f; font-size:12px; }
    .footer__inner { display:flex; justify-content:space-between; gap:20px; }
    @media(max-width:800px) {
      .nav { height:78px; }
      .brand img { width:145px; }
      .back { display:none; }
      .hero { min-height:540px; padding-top:145px; }
      .visual-story { margin-top:-48px; }
      .visual-story__grid { grid-template-columns:1fr 1fr; grid-template-rows:390px 230px; }
      .story-image--main { grid-column:1/-1; grid-row:auto; }
      .article { grid-template-columns:1fr; gap:55px; padding:70px 0; }
      .sidebar { position:static; }
      .cta__inner { display:block; }
      .cta .btn { margin-top:28px; }
    }
    @media(max-width:520px) {
      .container { width:calc(100% - 28px); }
      h1 { font-size:48px; }
      .hero__lead,.article__body #articleParagraphs p { font-size:16px; }
      .visual-story__grid { display:grid; grid-template-columns:1fr; grid-template-rows:330px 220px 220px; gap:12px; padding:12px; border-radius:22px; }
      .story-image--main { grid-column:auto; }
      .story-image { border-radius:15px; }
      .image-lightbox { padding:12px; }
      .image-lightbox__image-wrap { height:72vh; border-radius:18px; }
      .image-lightbox__nav { width:42px; height:42px; }
      .image-lightbox__prev { left:9px; }
      .image-lightbox__next { right:9px; }
      .image-lightbox__caption { margin-inline:45px; }
      .footer__inner { flex-direction:column; }
    }
  </style>
</head>
<body>
  @include('website.partials.tracking-body')
  <header class="header">
    <div class="container nav">
      <a class="brand" href="{{ route('home') }}#home" aria-label="AYAS FOODLINK home"><img src="{{ $media['logo'] }}" alt="AYAS FOODLINK"></a>
      <div class="nav__right">
        <a class="back" href="{{ route('home') }}#news" id="backLink">← Back to News</a>
        <div class="language-switch" role="group" aria-label="Choose language">
          <button type="button" data-language="id" aria-pressed="false">ID</button>
          <button type="button" data-language="en" aria-pressed="true">EN</button>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="hero" id="articleHero">
      <div class="container">
        <div class="eyebrow" id="articleCategory">News & Activities</div>
        <h1 id="articleTitle">Supply Progress</h1>
        <p class="hero__lead" id="articleLead"></p>
      </div>
    </section>

    <section class="visual-story" id="gallerySection" aria-label="{{ $translations['en']['postGalleryLabel'] }}">
      <div class="container">
        <div class="visual-story__grid">
          <button class="story-image story-image--main" type="button" data-gallery-index="0"><img id="galleryImage1" src="" alt=""><span class="story-image__caption" id="galleryCaption1"></span><span class="story-image__zoom" aria-hidden="true">＋</span></button>
          <button class="story-image" type="button" data-gallery-index="1"><img id="galleryImage2" src="" alt=""><span class="story-image__caption" id="galleryCaption2"></span><span class="story-image__zoom" aria-hidden="true">＋</span></button>
          <button class="story-image" type="button" data-gallery-index="2"><img id="galleryImage3" src="" alt=""><span class="story-image__caption" id="galleryCaption3"></span><span class="story-image__zoom" aria-hidden="true">＋</span></button>
        </div>
      </div>
    </section>

    <div class="container article">
      <article class="article__body">
        <div id="articleParagraphs"></div>
        <h2 id="articleSectionTitle"></h2>
        <ul id="articlePoints"></ul>
        <div class="article__note" id="articleNote"></div>
      </article>
      <aside class="sidebar">
        <div class="sidebar__label" id="otherNewsLabel">Other news</div>
        <nav class="related" aria-label="Other news">
          @foreach($relatedPosts as $related)<a href="{{ route('posts.show',$related) }}" @class(['is-active'=>$related->is($post)])><strong data-content-en="{{ $related->title }}" data-content-id="{{ $related->title_id ?: $related->title }}">{{ $related->title }}</strong><span data-content-en="{{ $related->category }}" data-content-id="{{ $related->category_id ?: $related->category }}">{{ $related->category }}</span></a>@endforeach
        </nav>
      </aside>
    </div>

    <section class="cta">
      <div class="container cta__inner">
        <h2 id="ctaTitle">Need product or supply information?</h2>
        <a class="btn" href="{{ route('home') }}#contact" id="ctaButton">Contact AYAS FOODLINK →</a>
      </div>
    </section>
  </main>

  <div class="image-lightbox" id="imageLightbox" hidden>
    <div class="image-lightbox__dialog" role="dialog" aria-modal="true" aria-labelledby="lightboxCaption">
      <div class="image-lightbox__image-wrap"><img id="lightboxImage" src="" alt=""></div>
      <span class="image-lightbox__count" id="lightboxCount">1 / 3</span>
      <button class="image-lightbox__close" id="lightboxClose" type="button" aria-label="Close image">&times;</button>
      <button class="image-lightbox__nav image-lightbox__prev" id="lightboxPrev" type="button" aria-label="Previous image">&#8592;</button>
      <button class="image-lightbox__nav image-lightbox__next" id="lightboxNext" type="button" aria-label="Next image">&#8594;</button>
      <div class="image-lightbox__caption" id="lightboxCaption"></div>
    </div>
  </div>

  <footer><div class="container footer__inner"><span>© <span id="year"></span> {{ $details['company_name'] }}.</span><a href="{{ route('home') }}#news" id="footerBack">Back to News</a></div></footer>

  @php
    $postImage = $post->image ? (str_starts_with($post->image, 'assets/') ? asset($post->image) : asset('storage/'.$post->image)) : asset('assets/images/warehouse-fallback.jpg');
    $resolvePostImage = fn ($value) => $value ? (str_starts_with($value, 'assets/') ? asset($value) : asset('storage/'.$value)) : $postImage;
    $postGallery = [$postImage, $resolvePostImage($post->gallery_image_2), $resolvePostImage($post->gallery_image_3)];
    $paragraphsEn = preg_split('/\R\R+/', $post->body);
    $paragraphsId = preg_split('/\R\R+/', $post->body_id ?: $post->body);
  @endphp
  <script>
    (() => {
      const content = {
        en: {
          common: { category:'News & Activities', back:'← Back to News', other:'Other news', ctaTitle:'Need product or supply information?', ctaButton:'Contact AYAS FOODLINK →', footerBack:'Back to News', note:'This page is ready to be updated with dates, photographs, locations, and verified activity details when the material is available.', types:['Supply update','Export update','Partner activity'] },
          supply: { title:'Supply Progress', lead:'A dedicated space for updates on product availability, fulfilment, and distribution across the AYAS FOODLINK supply network.', paragraphs:['AYAS FOODLINK continues to develop practical and dependable coordination between producers, products, and customer requirements. This detail page will document meaningful supply developments as they happen.','Updates can include new product availability, fulfilment progress, distribution coverage, and improvements that help customers plan their operational needs more confidently.'], section:'Updates that will be documented', points:['Product availability and new supply opportunities','Order fulfilment and distribution progress','Quality, consistency, and supply-chain coordination'], image:'assets/images/warehouse-fallback.jpg' },
          export: { title:'Export Activities', lead:'Updates on export preparation, product readiness, and milestones in serving international market requirements.', paragraphs:['This page is prepared to share verified developments from AYAS FOODLINK export activities. Each update can explain the product, destination, preparation process, and partners involved.','The focus is on clear information that helps customers and partners understand product readiness and the coordination required for dependable export supply.'], section:'Export stories that can be shared', points:['Export-ready products and market destinations','Preparation, documentation, and fulfilment milestones','Collaboration with customers and supply partners'], image:'assets/images/tortilla-fallback.jpg' },
          factory: { title:'Partner Factory Visits', lead:'A closer look at the producers, facilities, and quality processes supporting AYAS FOODLINK products.', paragraphs:['Factory visits provide an opportunity to understand production capabilities, quality practices, and the teams behind the products supplied by AYAS FOODLINK.','Future updates can present verified visit documentation, production insights, and the steps taken with partners to maintain dependable products and supply chains.'], section:'What each visit can highlight', points:['Producer profile and manufacturing capabilities','Quality-control and product-handling processes','Collaboration supporting reliable ongoing supply'], image:'assets/images/horeca-fallback.jpg' }
        },
        id: {
          common: { category:'Berita & Kegiatan', back:'← Kembali ke Berita', other:'Berita lainnya', ctaTitle:'Membutuhkan informasi produk atau pasokan?', ctaButton:'Hubungi AYAS FOODLINK →', footerBack:'Kembali ke Berita', note:'Halaman ini siap diperbarui dengan tanggal, foto, lokasi, dan detail kegiatan terverifikasi setelah materinya tersedia.', types:['Kabar pasokan','Kabar ekspor','Kegiatan mitra'] },
          supply: { title:'Perkembangan Pasokan', lead:'Ruang khusus untuk kabar ketersediaan produk, pemenuhan pesanan, dan distribusi dalam jaringan pasokan AYAS FOODLINK.', paragraphs:['AYAS FOODLINK terus mengembangkan koordinasi yang praktis dan dapat diandalkan antara produsen, produk, dan kebutuhan pelanggan. Halaman detail ini akan mendokumentasikan perkembangan pasokan penting ketika kegiatannya berlangsung.','Kabar dapat mencakup ketersediaan produk baru, perkembangan pemenuhan pesanan, jangkauan distribusi, dan peningkatan yang membantu pelanggan merencanakan kebutuhan operasional dengan lebih baik.'], section:'Perkembangan yang akan didokumentasikan', points:['Ketersediaan produk dan peluang pasokan baru','Perkembangan pemenuhan pesanan dan distribusi','Mutu, konsistensi, dan koordinasi rantai pasok'], image:'assets/images/warehouse-fallback.jpg' },
          export: { title:'Kegiatan Ekspor', lead:'Kabar mengenai persiapan ekspor, kesiapan produk, dan pencapaian dalam melayani kebutuhan pasar internasional.', paragraphs:['Halaman ini disiapkan untuk membagikan perkembangan terverifikasi dari kegiatan ekspor AYAS FOODLINK. Setiap kabar dapat menjelaskan produk, negara tujuan, proses persiapan, dan mitra yang terlibat.','Fokusnya adalah informasi jelas yang membantu pelanggan dan mitra memahami kesiapan produk serta koordinasi yang diperlukan untuk pasokan ekspor yang dapat diandalkan.'], section:'Kabar ekspor yang dapat dibagikan', points:['Produk siap ekspor dan negara tujuan','Persiapan, dokumentasi, dan pencapaian pemenuhan','Kolaborasi dengan pelanggan dan mitra pasokan'], image:'assets/images/tortilla-fallback.jpg' },
          factory: { title:'Kunjungan Pabrik Mitra', lead:'Mengenal lebih dekat produsen, fasilitas, dan proses mutu yang mendukung produk AYAS FOODLINK.', paragraphs:['Kunjungan pabrik menjadi kesempatan untuk memahami kemampuan produksi, praktik mutu, dan tim di balik produk yang dipasok oleh AYAS FOODLINK.','Kabar berikutnya dapat menampilkan dokumentasi kunjungan terverifikasi, wawasan produksi, dan langkah bersama mitra untuk menjaga produk serta rantai pasok yang dapat diandalkan.'], section:'Hal yang dapat ditampilkan dari setiap kunjungan', points:['Profil produsen dan kemampuan produksi','Proses pengendalian mutu dan penanganan produk','Kolaborasi yang mendukung kesinambungan pasokan'], image:'assets/images/horeca-fallback.jpg' }
        }
      };
      content.en.supply = { title: @json($post->title), lead: @json($post->excerpt), paragraphs: @json($paragraphsEn), section: '', points: [], image: @json($postImage) };
      content.id.supply = { title: @json($post->title_id ?: $post->title), lead: @json($post->excerpt_id ?: $post->excerpt), paragraphs: @json($paragraphsId), section: '', points: [], image: @json($postImage) };
      content.en.common = { category:@json($post->category), back:@json($translations['en']['postBack']), other:@json($translations['en']['postOther']), ctaTitle:@json($translations['en']['postCtaTitle']), ctaButton:@json($translations['en']['postCtaButton']), footerBack:@json($translations['en']['postFooterBack']), note:@json($translations['en']['postNote']), galleryLabel:@json($translations['en']['postGalleryLabel']) };
      content.id.common = { category:@json($post->category_id ?: $post->category), back:@json($translations['id']['postBack']), other:@json($translations['id']['postOther']), ctaTitle:@json($translations['id']['postCtaTitle']), ctaButton:@json($translations['id']['postCtaButton']), footerBack:@json($translations['id']['postFooterBack']), note:@json($translations['id']['postNote']), galleryLabel:@json($translations['id']['postGalleryLabel']) };

      const galleryImages = {
        supply: {!! json_encode($postGallery, JSON_UNESCAPED_SLASHES) !!},
        export: ['assets/images/tortilla-fallback.jpg','assets/images/gelato-fallback.jpg','assets/images/warehouse-fallback.jpg'],
        factory: ['assets/images/warehouse-fallback.jpg','assets/images/chicken-fallback.jpg','assets/images/brisket-fallback.jpg']
      };
      const galleryCaptions = {
        en: {
          supply: [@json($post->gallery_caption_1 ?: $post->title),@json($post->gallery_caption_2 ?: $post->category),@json($post->gallery_caption_3 ?: $post->title)],
          export: ['Export-ready product portfolio','Product variety for international markets','Supply preparation and coordination'],
          factory: ['Facilities supporting dependable supply','Product handling and preparation','Quality products from trusted producers']
        },
        id: {
          supply: [@json($post->gallery_caption_1_id ?: $post->gallery_caption_1 ?: $post->title_id ?: $post->title),@json($post->gallery_caption_2_id ?: $post->gallery_caption_2 ?: $post->category_id ?: $post->category),@json($post->gallery_caption_3_id ?: $post->gallery_caption_3 ?: $post->title_id ?: $post->title)],
          export: ['Portofolio produk siap ekspor','Ragam produk untuk pasar internasional','Persiapan dan koordinasi pasokan'],
          factory: ['Fasilitas yang mendukung pasokan terpercaya','Penanganan dan persiapan produk','Produk berkualitas dari produsen terpercaya']
        }
      };

      const articleKey = 'supply';
      const languageButtons = [...document.querySelectorAll('[data-language]')];
      let language = 'en';
      try { language = localStorage.getItem('ayas-language') === 'id' ? 'id' : 'en'; } catch (_) {}

      const imageLightbox = document.getElementById('imageLightbox');
      const lightboxImage = document.getElementById('lightboxImage');
      const lightboxCaption = document.getElementById('lightboxCaption');
      const lightboxCount = document.getElementById('lightboxCount');
      const lightboxClose = document.getElementById('lightboxClose');
      let lightboxIndex = 0;
      let lastGalleryButton = null;

      const showLightboxImage = index => {
        lightboxIndex = (index + 3) % 3;
        const caption = galleryCaptions[language][articleKey][lightboxIndex];
        lightboxImage.src = galleryImages[articleKey][lightboxIndex];
        lightboxImage.alt = caption;
        lightboxCaption.textContent = caption;
        lightboxCount.textContent = `${lightboxIndex + 1} / 3`;
      };
      const openLightbox = (index, trigger) => {
        lastGalleryButton = trigger;
        showLightboxImage(index);
        imageLightbox.hidden = false;
        document.body.classList.add('lightbox-open');
        lightboxClose.focus();
      };
      const closeLightbox = () => {
        imageLightbox.hidden = true;
        document.body.classList.remove('lightbox-open');
        if (lastGalleryButton) lastGalleryButton.focus();
      };

      document.querySelectorAll('[data-gallery-index]').forEach(button => button.addEventListener('click', () => openLightbox(Number(button.dataset.galleryIndex), button)));
      lightboxClose.addEventListener('click', closeLightbox);
      document.getElementById('lightboxPrev').addEventListener('click', () => showLightboxImage(lightboxIndex - 1));
      document.getElementById('lightboxNext').addEventListener('click', () => showLightboxImage(lightboxIndex + 1));
      imageLightbox.addEventListener('click', event => { if (event.target === imageLightbox) closeLightbox(); });
      document.addEventListener('keydown', event => {
        if (imageLightbox.hidden) return;
        if (event.key === 'Escape') closeLightbox();
        if (event.key === 'ArrowLeft') showLightboxImage(lightboxIndex - 1);
        if (event.key === 'ArrowRight') showLightboxImage(lightboxIndex + 1);
      });

      const render = () => {
        const common = content[language].common;
        const article = content[language][articleKey];
        document.documentElement.lang = language;
        document.title = `${article.title} — {{ $details['company_name'] }}`;
        document.getElementById('articleHero').style.setProperty('--hero-image', `url("${article.image}")`);
        document.getElementById('articleCategory').textContent = common.category;
        document.getElementById('articleTitle').textContent = article.title;
        document.getElementById('articleLead').textContent = article.lead;
        galleryImages[articleKey].forEach((source,index) => {
          const caption = galleryCaptions[language][articleKey][index];
          const image = document.getElementById(`galleryImage${index + 1}`);
          image.src = source;
          image.alt = caption;
          document.getElementById(`galleryCaption${index + 1}`).textContent = caption;
        });
        if (!imageLightbox.hidden) showLightboxImage(lightboxIndex);
        document.getElementById('articleParagraphs').innerHTML = article.paragraphs.map(text => `<p>${text}</p>`).join('');
        document.getElementById('articleSectionTitle').textContent = article.section;
        document.getElementById('articlePoints').innerHTML = article.points.map(text => `<li>${text}</li>`).join('');
        document.getElementById('articleSectionTitle').hidden = !article.section;
        document.getElementById('articlePoints').hidden = !article.points.length;
        document.getElementById('articleNote').textContent = common.note;
        document.getElementById('backLink').textContent = common.back;
        document.getElementById('otherNewsLabel').textContent = common.other;
        document.getElementById('ctaTitle').textContent = common.ctaTitle;
        document.getElementById('ctaButton').textContent = common.ctaButton;
        document.getElementById('footerBack').textContent = common.footerBack;
        document.getElementById('gallerySection').setAttribute('aria-label', common.galleryLabel);
        document.querySelectorAll('[data-content-en]').forEach(el => {
          el.textContent = el.dataset[language === 'id' ? 'contentId' : 'contentEn'];
        });
        languageButtons.forEach(button => {
          const active = button.dataset.language === language;
          button.classList.toggle('is-active', active);
          button.setAttribute('aria-pressed', String(active));
        });
      };

      languageButtons.forEach(button => button.addEventListener('click', () => {
        language = button.dataset.language === 'id' ? 'id' : 'en';
        try { localStorage.setItem('ayas-language', language); } catch (_) {}
        render();
      }));
      document.getElementById('year').textContent = new Date().getFullYear();
      render();
    })();
  </script>
</body>
</html>
