<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('website.partials.tracking-head')
  @include('website.partials.seo')
  <meta name="theme-color" content="#071f3d">

  <link rel="icon" type="image/png" href="{{ $media['favicon'] }}">
  <link rel="apple-touch-icon" href="{{ $media['favicon'] }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">

  <style>
    :root {
      --navy: #071f3d;
      --navy-2: #0b2e57;
      --gold: #c89543;
      --gold-light: #e7c17d;
      --ivory: #f5f0e7;
      --paper: #fbf9f4;
      --ink: #0c2442;
      --muted: #6f7882;
      --line: rgba(7, 31, 61, .14);
      --white: #fff;
      --shadow: 0 30px 80px rgba(7, 31, 61, .13);
      --radius: 28px;
      --ease: cubic-bezier(.2,.75,.2,1);
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; overflow-x: clip; }
    body {
      margin: 0;
      color: var(--ink);
      background: var(--paper);
      font-family: "DM Sans", sans-serif;
      overflow-x: hidden;
    }
    img { max-width: 100%; display: block; }
    a { color: inherit; text-decoration: none; }
    button, input, textarea, select { font: inherit; }
    button { border: 0; }
    ::selection { background: var(--gold); color: var(--navy); }
    .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }

    /* UTILITIES */
    .container { width: min(1180px, calc(100% - 48px)); margin: 0 auto; }
    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
    }
    .eyebrow::before { content: ""; width: 38px; height: 1px; background: currentColor; opacity: .75; }
    .display {
      font-family: "Playfair Display", serif;
      font-weight: 500;
      letter-spacing: -.035em;
      line-height: .96;
      margin: 0;
    }
    .section { padding: 120px 0; position: relative; }
    .section-title { font-size: clamp(44px, 5.8vw, 82px); max-width: 900px; }
    .muted { color: var(--muted); }

    .btn {
      position: relative;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      min-height: 56px;
      padding: 0 25px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      overflow: hidden;
      transition: transform .35s var(--ease), box-shadow .35s var(--ease), background .35s var(--ease);
      cursor: pointer;
    }
    .btn::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,.16);
      transform: translateX(-110%) skewX(-18deg);
      transition: transform .55s var(--ease);
    }
    .btn:hover::before { transform: translateX(110%) skewX(-18deg); }
    .btn:hover { transform: translateY(-3px); }
    .btn--gold { background: var(--gold); color: var(--navy); box-shadow: 0 16px 38px rgba(200,149,67,.28); }
    .btn--outline { border: 1px solid rgba(255,255,255,.28); color: white; background: rgba(255,255,255,.04); backdrop-filter: blur(12px); }
    .btn__arrow { width: 19px; height: 19px; transition: transform .35s var(--ease); }
    .btn:hover .btn__arrow { transform: translateX(4px); }

    /* HEADER */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
      transition: background .35s var(--ease), box-shadow .35s var(--ease), padding .35s var(--ease);
    }
    .header.is-scrolled { background: rgba(7,31,61,.93); backdrop-filter: blur(16px); box-shadow: 0 8px 40px rgba(0,0,0,.14); }
    .nav { height: 92px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .brand { display: flex; align-items: center; flex-shrink: 0; }
    .brand img { width: 174px; height: auto; }
    .nav__links { display: flex; align-items: center; gap: 27px; color: rgba(255,255,255,.8); font-size: 13px; font-weight: 600; }
    .nav__links a { position: relative; padding: 8px 0; }
    .nav__links a::after { content: ""; position: absolute; left: 0; right: 100%; bottom: 0; height: 1px; background: var(--gold); transition: right .35s var(--ease); }
    .nav__links a:hover::after { right: 0; }
    .nav__cta { display: inline-flex; align-items: center; gap: 9px; color: var(--gold-light); font-size: 12px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
    .nav__actions { display:flex; align-items:center; gap:18px; flex-shrink:0; }
    .language-switch { display:inline-flex; align-items:center; gap:3px; padding:3px; border:1px solid rgba(255,255,255,.22); border-radius:999px; background:rgba(255,255,255,.08); }
    .language-switch button { min-width:39px; height:31px; padding:0 9px; border-radius:999px; color:rgba(255,255,255,.66); background:transparent; font-size:11px; font-weight:700; letter-spacing:.08em; cursor:pointer; transition:color .25s, background .25s, box-shadow .25s; }
    .language-switch button.is-active { color:var(--navy); background:var(--gold-light); box-shadow:0 4px 15px rgba(0,0,0,.18); }
    .language-switch button:focus-visible { outline:2px solid var(--gold-light); outline-offset:2px; }
    .nav__dot { width: 8px; height: 8px; background: var(--gold); border-radius: 50%; box-shadow: 0 0 0 6px rgba(200,149,67,.15); animation: pulse 2s infinite; }
    @keyframes pulse { 50% { box-shadow: 0 0 0 11px rgba(200,149,67,0); } }
    .menu-btn { display: none; width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,.09); color: white; align-items: center; justify-content: center; cursor: pointer; }
    .menu-btn span, .menu-btn span::before, .menu-btn span::after { display:block; width:19px; height:1px; background:currentColor; transition:.3s; }
    .menu-btn span { position:relative; }
    .menu-btn span::before,.menu-btn span::after { content:""; position:absolute; left:0; }
    .menu-btn span::before{ top:-6px; }.menu-btn span::after{ top:6px; }
    .menu-btn.active span{ background:transparent; }.menu-btn.active span::before{ top:0; transform:rotate(45deg); }.menu-btn.active span::after{ top:0; transform:rotate(-45deg); }

    /* HERO */
    .hero {
      min-height: 100svh;
      display: grid;
      align-items: center;
      position: relative;
      overflow: hidden;
      color: white;
      background: var(--navy);
    }
    .hero::before {
      content: "";
      position: absolute;
      width: 680px;
      height: 680px;
      border-radius: 50%;
      left: -260px;
      top: 30%;
      background: radial-gradient(circle, rgba(200,149,67,.2), transparent 67%);
      animation: floatOrb 10s ease-in-out infinite;
    }
    @keyframes floatOrb { 50% { transform: translate(45px,-40px) scale(1.08); } }
    .hero__image {
      position: absolute;
      inset: 0 0 0 49%;
      overflow: hidden;
      clip-path: ellipse(72% 92% at 76% 48%);
      background: linear-gradient(135deg, #162a3f, #493219);
    }
    .hero__image::after { content:""; position:absolute; inset:0; background:linear-gradient(90deg,var(--navy) 0%,rgba(7,31,61,.15) 45%,rgba(7,31,61,.08)); }
    .hero__image img { width: 100%; height: 100%; object-fit: cover; opacity:.86; transform: scale(1.08); animation: heroZoom 8s var(--ease) forwards; }
    @keyframes heroZoom { to { transform: scale(1); } }
    .hero__grain { position:absolute; inset:0; pointer-events:none; opacity:.06; background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 220 220' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.55'/%3E%3C/svg%3E"); }
    .hero__content { position: relative; z-index: 3; padding-top: 100px; width: min(700px, 60%); }
    .hero__eyebrow { color: var(--gold-light); margin-bottom: 34px; opacity:0; transform:translateY(18px); animation: heroIn .8s .1s var(--ease) forwards; }
    .hero h1 { font-size: clamp(58px, 7.6vw, 112px); max-width: 760px; opacity:0; transform:translateY(35px); animation: heroIn 1s .2s var(--ease) forwards; }
    .hero h1 em { color: var(--gold-light); font-weight:500; }
    .hero__text { max-width: 565px; margin: 30px 0 38px; font-size: clamp(16px,1.4vw,19px); line-height:1.75; color:rgba(255,255,255,.72); opacity:0; transform:translateY(25px); animation:heroIn .9s .35s var(--ease) forwards; }
    .hero__actions { display:flex; flex-wrap:wrap; gap:14px; opacity:0; transform:translateY(25px); animation:heroIn .9s .5s var(--ease) forwards; }
    @keyframes heroIn { to { opacity:1; transform:none; } }
    .hero__stats {
      position:absolute;
      right:44px;
      bottom:38px;
      z-index:4;
      display:grid;
      grid-template-columns:repeat(3,auto);
      gap:28px;
      padding:20px 24px;
      border:1px solid rgba(255,255,255,.16);
      background:rgba(7,31,61,.54);
      backdrop-filter:blur(14px);
      border-radius:18px;
      opacity:0;
      transform:translateY(20px);
      animation:heroIn .8s .65s var(--ease) forwards;
    }
    .hero__stat strong { display:block; font-family:"Playfair Display",serif; font-size:26px; color:var(--gold-light); }
    .hero__stat span { font-size:10px; letter-spacing:.11em; text-transform:uppercase; color:rgba(255,255,255,.62); }
    /* MARQUEE */
    .marquee { overflow:hidden; background:var(--gold); color:var(--navy); border-block:1px solid rgba(7,31,61,.15); }
    .marquee__track { display:flex; width:max-content; animation:marquee 24s linear infinite; }
    .marquee__group { display:flex; align-items:center; gap:28px; padding:17px 14px; }
    .marquee__item { display:flex; align-items:center; gap:28px; white-space:nowrap; font-size:12px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; }
    .marquee__item::after { content:"✦"; font-size:15px; }
    @keyframes marquee { to { transform:translateX(-50%); } }

    /* ABOUT */
    .about { background:var(--paper); }
    .about__head { display:grid; grid-template-columns: 1.15fr .85fr; gap:75px; align-items:end; margin-bottom:80px; }
    .about__headline { display:grid; gap:34px; }
    .about__intro { font-size:clamp(32px,4vw,58px); line-height:1.12; }
    .about__intro em { color:var(--gold); }
    .about__copy { max-width:560px; margin-left:auto; padding-top:12px; font-size:17px; line-height:1.8; color:var(--muted); }
    .about__grid { display:grid; grid-template-columns:1.2fr .8fr; gap:24px; }
    .about__visual { position:relative; min-height:560px; border-radius:var(--radius); overflow:hidden; background:#d4c5ad; box-shadow:var(--shadow); }
    .about__visual img { width:100%; height:100%; object-fit:cover; transition:transform 1.2s var(--ease); }
    .about__visual:hover img { transform:scale(1.045); }
    .about__visual::after { content:""; position:absolute; inset:0; background:linear-gradient(180deg,transparent 45%,rgba(7,31,61,.78)); }
    .about__badge { position:absolute; left:30px; bottom:30px; z-index:2; color:white; max-width:330px; }
    .about__badge strong { display:block; font-family:"Playfair Display",serif; font-size:32px; margin-bottom:7px; }
    .about__badge span { color:rgba(255,255,255,.72); line-height:1.55; }
    .values { display:grid; gap:16px; }
    .value-card { position:relative; padding:30px; min-height:170px; border:1px solid var(--line); border-radius:22px; background:white; overflow:hidden; transition:transform .4s var(--ease), box-shadow .4s var(--ease), border-color .4s; }
    .value-card:hover { transform:translateY(-7px); box-shadow:0 22px 50px rgba(7,31,61,.1); border-color:rgba(200,149,67,.55); }
    .value-card__num { position:absolute; right:22px; top:16px; font-family:"Playfair Display",serif; font-size:56px; color:rgba(200,149,67,.16); }
    .value-card__icon { width:42px; height:42px; margin-bottom:22px; color:var(--gold); }
    .value-card h3 { margin:0 0 8px; font-family:"Playfair Display",serif; font-size:25px; }
    .value-card p { margin:0; color:var(--muted); line-height:1.6; font-size:14px; }

    /* PRODUCTS */
    .products { background:var(--navy); color:white; overflow:hidden; }
    .products::before { content:""; position:absolute; width:520px; height:520px; border:1px solid rgba(200,149,67,.19); border-radius:50%; right:-180px; top:80px; }
    .products__top { display:flex; justify-content:space-between; align-items:end; gap:40px; margin-bottom:58px; }
    .products__top p { max-width:420px; line-height:1.75; color:rgba(255,255,255,.62); }
    .product-grid { display:grid; grid-template-columns:repeat(12,1fr); grid-auto-rows:74px; gap:18px; }
    .product-card { position:relative; width:100%; padding:0; border-radius:24px; overflow:hidden; isolation:isolate; color:white; text-align:left; cursor:pointer; background:#24364b; }
    .product-card:nth-child(1){ grid-column:span 7; grid-row:span 6; }
    .product-card:nth-child(2){ grid-column:span 5; grid-row:span 4; }
    .product-card:nth-child(3){ grid-column:span 5; grid-row:span 5; }
    .product-card:nth-child(4){ grid-column:span 7; grid-row:span 3; }
    .product-grid--extended { grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); grid-auto-rows:360px; }
    .product-grid--extended .product-card:nth-child(n) { grid-column:auto; grid-row:auto; }
    .product-card img { width:100%; height:100%; object-fit:cover; transition:transform .9s var(--ease), filter .6s var(--ease); }
    .product-card::after { content:""; position:absolute; inset:0; z-index:1; background:linear-gradient(180deg,rgba(7,31,61,.03) 30%,rgba(7,31,61,.88) 100%); }
    .product-card:hover img { transform:scale(1.07); filter:saturate(1.08); }
    .product-card:focus-visible { outline:3px solid var(--gold-light); outline-offset:4px; }
    .product-card__info { position:absolute; z-index:2; left:28px; right:28px; bottom:25px; display:flex; justify-content:space-between; align-items:end; gap:20px; }
    .product-card__info h3 { margin:0; font-family:"Playfair Display",serif; font-size:clamp(25px,2.4vw,40px); }
    .product-card__info p { margin:4px 0 0; color:rgba(255,255,255,.65); font-size:13px; }
    .product-card__tag { padding:8px 12px; border:1px solid rgba(255,255,255,.22); border-radius:999px; font-size:10px; letter-spacing:.13em; text-transform:uppercase; white-space:nowrap; backdrop-filter:blur(12px); }

    /* PRODUCT DETAIL */
    .product-modal { position:fixed; inset:0; z-index:300; display:grid; place-items:center; padding:24px; background:rgba(3,15,31,.82); backdrop-filter:blur(12px); }
    .product-modal[hidden] { display:none; }
    .product-modal__dialog { position:relative; display:grid; grid-template-columns:1fr 1fr; width:min(920px,100%); max-height:calc(100svh - 48px); overflow:auto; border-radius:28px; color:var(--ink); background:var(--paper); box-shadow:0 35px 100px rgba(0,0,0,.38); }
    .product-modal__media { position:relative; min-height:520px; overflow:hidden; background:#24364b; }
    .product-modal__track { display:flex; width:100%; height:100%; overflow-x:auto; scroll-snap-type:x mandatory; scrollbar-width:none; cursor:grab; touch-action:pan-y; }
    .product-modal__track::-webkit-scrollbar { display:none; }
    .product-modal__track.is-dragging { cursor:grabbing; scroll-snap-type:none; }
    .product-modal__slide { flex:0 0 100%; min-width:100%; height:100%; scroll-snap-align:start; }
    .product-modal__slide img { width:100%; height:100%; object-fit:cover; pointer-events:none; user-select:none; }
    .product-modal__controls { position:absolute; left:50%; bottom:18px; z-index:3; display:flex; align-items:center; gap:10px; padding:7px; border-radius:999px; background:rgba(7,31,61,.78); backdrop-filter:blur(10px); transform:translateX(-50%); }
    .product-modal__controls[hidden] { display:none; }
    .product-modal__control { display:grid; place-items:center; width:36px; height:36px; border-radius:50%; color:white; background:rgba(255,255,255,.12); font-size:19px; cursor:pointer; }
    .product-modal__control:hover { color:var(--navy); background:var(--gold-light); }
    .product-modal__count { min-width:34px; color:white; text-align:center; font-size:11px; font-weight:700; letter-spacing:.08em; }
    .product-modal__content { display:flex; flex-direction:column; justify-content:center; padding:54px; }
    .product-modal__tag { align-self:flex-start; margin-bottom:22px; padding:8px 12px; border-radius:999px; color:var(--navy); background:var(--gold-light); font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }
    .product-modal h3 { margin:0 0 18px; font-family:"Playfair Display",serif; font-size:clamp(42px,5vw,66px); line-height:1; }
    .product-modal p { margin:0 0 30px; color:var(--muted); font-size:16px; line-height:1.75; }
    .product-modal__close { position:absolute; top:18px; right:18px; z-index:2; width:44px; height:44px; border-radius:50%; color:white; background:rgba(7,31,61,.78); font-size:25px; line-height:1; cursor:pointer; }
    .product-modal__close:focus-visible { outline:3px solid var(--gold-light); outline-offset:3px; }
    body.modal-open { overflow:hidden; }

    /* CAPABILITIES */
    .capabilities { background:var(--ivory); }
    .capabilities__grid { display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center; }
    .capabilities__image { position:relative; min-height:670px; }
    .capabilities__image-main { position:absolute; inset:0 14% 10% 0; border-radius:28px; overflow:hidden; box-shadow:var(--shadow); background:#c6b69e; }
    .capabilities__image-main img { width:100%; height:100%; object-fit:cover; }
    .capabilities__image-small { position:absolute; width:46%; height:40%; right:0; bottom:0; border:10px solid var(--ivory); border-radius:28px; overflow:hidden; box-shadow:0 25px 70px rgba(7,31,61,.18); background:#d6c7b0; }
    .capabilities__image-small img { width:100%; height:100%; object-fit:cover; }
    .capabilities__badge { position:absolute; right:3%; top:6%; max-width:190px; padding:14px 18px; border:1px solid rgba(200,149,67,.7); border-radius:999px; display:flex; align-items:center; gap:10px; color:var(--navy); background:rgba(251,249,244,.88); backdrop-filter:blur(8px); box-shadow:0 12px 28px rgba(7,31,61,.12); }
    .capabilities__badge::before { content:""; width:8px; height:8px; flex:0 0 auto; border-radius:50%; background:var(--gold); }
    .capabilities__badge span { font-size:9px; line-height:1.45; font-weight:700; letter-spacing:.11em; text-transform:uppercase; }
    .capabilities__copy h2 { font-size:clamp(48px,5vw,76px); margin:20px 0 25px; }
    .capabilities__copy > p { color:var(--muted); font-size:17px; line-height:1.8; max-width:560px; }
    .metric-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; margin:42px 0; }
    .metric { padding:24px 0; border-top:1px solid var(--line); }
    .metric strong { display:flex; align-items:baseline; min-height:54px; font-family:"Playfair Display",serif; font-size:45px; line-height:1.1; color:var(--gold); }
    .metric strong .counter { font:inherit; letter-spacing:inherit; text-transform:none; color:inherit; }
    .metric > span { font-size:12px; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); }

    /* PROCESS */
    .process { background:white; }
    .process__head { display:flex; justify-content:space-between; align-items:end; gap:40px; margin-bottom:70px; }
    .process__head p { max-width:430px; color:var(--muted); line-height:1.7; }
    .steps { display:grid; grid-template-columns:repeat(4,1fr); border-top:1px solid var(--line); }
    .step { position:relative; padding:38px 28px 10px 0; border-right:1px solid var(--line); min-height:260px; }
    .step:last-child { border-right:0; padding-right:0; padding-left:28px; }
    .step:not(:first-child) { padding-left:28px; }
    .step::before { content:""; position:absolute; top:-5px; left:0; width:9px; height:9px; border-radius:50%; background:var(--gold); box-shadow:0 0 0 8px white; }
    .step__num { font-size:11px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--gold); }
    .step h3 { font-family:"Playfair Display",serif; font-size:28px; margin:55px 0 12px; }
    .step p { color:var(--muted); line-height:1.7; font-size:14px; }

    /* NEWS */
    .news { color:white; background:linear-gradient(145deg,#0a2a50,var(--navy)); overflow:hidden; }
    .news::before { content:""; position:absolute; width:560px; height:560px; right:-260px; top:-260px; border:1px solid rgba(231,193,125,.17); border-radius:50%; box-shadow:0 0 0 90px rgba(231,193,125,.025),0 0 0 180px rgba(231,193,125,.018); }
    .news__head { position:relative; display:flex; justify-content:space-between; align-items:end; gap:40px; margin-bottom:54px; }
    .news__head p { max-width:440px; margin:0; color:rgba(255,255,255,.64); line-height:1.75; }
    .news-grid { position:relative; display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
    .news-card { overflow:hidden; border:1px solid rgba(255,255,255,.12); border-radius:24px; background:rgba(255,255,255,.055); }
    .news-card__image { height:235px; overflow:hidden; background:#24364b; }
    .news-card__image img { width:100%; height:100%; object-fit:cover; transition:transform .8s var(--ease); }
    .news-card:hover .news-card__image img { transform:scale(1.055); }
    .news-card__body { padding:28px; }
    .news-card__meta { display:block; margin-bottom:16px; color:var(--gold-light); font-size:10px; font-weight:700; letter-spacing:.15em; text-transform:uppercase; }
    .news-card h3 { margin:0 0 12px; font-family:"Playfair Display",serif; font-size:29px; }
    .news-card p { margin:0; color:rgba(255,255,255,.64); font-size:14px; line-height:1.7; }
    .news-card__link { display:inline-flex; align-items:center; gap:8px; margin-top:20px; color:var(--gold-light); font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }

    /* CTA / CONTACT */
    .contact { padding:110px 0 0; color:white; background:var(--navy); }
    .contact__panel { position:relative; display:grid; grid-template-columns:.86fr 1.14fr; gap:64px; align-items:center; overflow:hidden; padding:76px; border:1px solid rgba(255,255,255,.1); border-radius:34px 34px 0 0; background:linear-gradient(135deg,#082443,#061b35); }
    .contact__panel::before { content:""; position:absolute; width:520px; height:520px; left:-260px; top:-250px; border:1px solid rgba(200,149,67,.22); border-radius:50%; box-shadow:0 0 0 90px rgba(200,149,67,.025),0 0 0 180px rgba(200,149,67,.018); }
    .contact__intro { position:relative; z-index:1; }
    .contact h2 { margin:20px 0 22px; font-size:clamp(48px,5.4vw,78px); }
    .contact h2 em { color:var(--gold-light); }
    .contact__text { max-width:500px; margin:0 0 34px; color:rgba(255,255,255,.67); line-height:1.75; font-size:16px; }
    .contact__details { display:grid; gap:18px; }
    .contact__detail { display:grid; grid-template-columns:42px 1fr; gap:14px; align-items:start; }
    .contact__icon { display:grid; place-items:center; width:42px; height:42px; border:1px solid rgba(200,149,67,.32); border-radius:50%; color:var(--gold-light); font-size:16px; }
    .contact__label { display:block; margin-bottom:4px; font-size:10px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--gold-light); }
    .contact__value { color:rgba(255,255,255,.76); line-height:1.6; font-size:14px; }
    .contact__value a:hover { color:var(--gold-light); }
    .contact-form { position:relative; z-index:1; padding:42px; border-radius:24px; color:var(--ink); background:white; box-shadow:0 30px 80px rgba(0,0,0,.22); }
    .contact-form h3 { margin:0 0 8px; font-family:"Playfair Display",serif; font-size:34px; }
    .contact-form__lead { margin:0 0 28px; color:var(--muted); font-size:14px; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
    .form-field { display:grid; gap:8px; }
    .form-field--full { grid-column:1/-1; }
    .form-field label { font-size:11px; font-weight:700; color:var(--ink); }
    .form-field input, .form-field select, .form-field textarea { width:100%; border:1px solid var(--line); border-radius:12px; color:var(--ink); background:var(--paper); outline:none; transition:border-color .25s, box-shadow .25s; }
    .form-field input, .form-field select { height:48px; padding:0 14px; }
    .form-field textarea { min-height:112px; padding:13px 14px; resize:vertical; }
    .form-field input:focus, .form-field select:focus, .form-field textarea:focus { border-color:var(--gold); box-shadow:0 0 0 3px rgba(200,149,67,.14); }
    .form-actions { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:20px; }
    .contact-form .form-actions .btn { width:100%; margin:0; padding-inline:18px; }
    .btn--email { border:1px solid var(--line); color:var(--navy); background:white; box-shadow:none; }
    .form-note { margin:12px 0 0; color:var(--muted); text-align:center; font-size:10px; }

    /* FOOTER */
    footer { background:var(--navy); color:white; padding:30px 0 34px; }
    .footer__inner { display:flex; justify-content:space-between; align-items:center; gap:30px; color:rgba(255,255,255,.44); font-size:12px; }
    .footer__links { display:flex; gap:24px; }
    .footer__links a:hover { color:white; }

    /* FLOATING WA */
    .wa {
      position:fixed;
      right:24px;
      bottom:24px;
      z-index:80;
      width:58px;
      height:58px;
      border-radius:50%;
      display:grid;
      place-items:center;
      color:var(--navy);
      background:var(--gold);
      box-shadow:0 18px 42px rgba(7,31,61,.28);
      transition:transform .35s var(--ease);
    }
    .wa:hover { transform:translateY(-5px) rotate(-5deg); }
    .wa svg { width:27px; height:27px; }

    /* SCROLL REVEAL */
    [data-reveal] { opacity:0; transform:translateY(38px); transition:opacity .9s var(--ease), transform .9s var(--ease); }
    [data-reveal="left"] { transform:translateX(-50px); }
    [data-reveal="right"] { transform:translateX(50px); }
    [data-reveal].is-visible { opacity:1; transform:none; }
    .delay-1{transition-delay:.1s}.delay-2{transition-delay:.2s}.delay-3{transition-delay:.3s}.delay-4{transition-delay:.4s}

    /* RESPONSIVE */
    @media (max-width: 1024px) {
      .nav__links { gap:18px; }
      .nav__cta { display:none; }
      .hero__content { width:68%; }
      .hero__image { left:42%; }
      .about__head { grid-template-columns:1fr; gap:30px; }
      .about__copy { margin-left:0; }
      .about__grid { grid-template-columns:1fr; }
      .values { grid-template-columns:repeat(3,1fr); }
      .value-card { min-height:210px; }
      .capabilities__grid { gap:45px; }
      .steps { grid-template-columns:repeat(2,1fr); row-gap:40px; }
      .step:nth-child(2) { border-right:0; }
      .news-grid { grid-template-columns:repeat(2,1fr); }
      .news-card:last-child { grid-column:1/-1; }
      .contact__panel { grid-template-columns:1fr; }
    }

    @media (min-width: 821px) and (max-height: 850px) {
      .hero { min-height:720px; }
      .hero__content { padding-top:72px; }
      .hero__eyebrow { margin-bottom:20px; }
      .hero h1 { max-width:680px; font-size:clamp(56px,6.3vw,86px); }
      .hero__text { margin:22px 0 26px; font-size:16px; line-height:1.6; }
      .hero__stats { right:28px; bottom:26px; padding:15px 18px; }
    }

    @media (max-width: 820px) {
      .container { width:min(100% - 32px, 1180px); }
      .section { padding:86px 0; }
      .nav { height:78px; }
      .brand img { width:145px; }
      .nav__links {
        position:fixed;
        inset:78px 0 auto;
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        padding:34px 24px 42px;
        background:rgba(7,31,61,.98);
        border-top:1px solid rgba(255,255,255,.08);
        transform:translateY(-120%);
        opacity:0;
        visibility:hidden;
        transition:.45s var(--ease);
      }
      .nav__links.open { transform:none; opacity:1; visibility:visible; }
      .nav__cta { display:none; }
      .menu-btn { display:flex; }
      .hero { min-height:900px; align-items:flex-start; }
      .hero__image { inset:46% 0 0 0; clip-path:ellipse(92% 72% at 66% 70%); }
      .hero__image::after { background:linear-gradient(180deg,var(--navy) 0%,rgba(7,31,61,.18) 45%); }
      .hero__content { width:100%; padding-top:150px; }
      .hero h1 { font-size:clamp(54px,14vw,82px); max-width:680px; }
      .hero__text { max-width:540px; }
      .hero__stats { display:none; }
      .about__grid { gap:18px; }
      .about__visual { min-height:470px; }
      .values { grid-template-columns:1fr; }
      .value-card { min-height:auto; }
      .products__top { display:block; }
      .products__top p { margin-top:25px; }
      .product-grid { display:grid; grid-template-columns:1fr 1fr; grid-auto-rows:310px; }
      .product-card:nth-child(n) { grid-column:auto; grid-row:auto; }
      .product-modal__dialog { grid-template-columns:1fr; }
      .product-modal__media { min-height:300px; max-height:38svh; }
      .product-modal__content { padding:38px; }
      .capabilities__grid { grid-template-columns:1fr; }
      .capabilities__image { min-height:580px; order:2; }
      .news__head { display:block; }
      .news__head p { margin-top:24px; }
      .contact { padding-top:80px; }
      .contact__panel { gap:45px; padding:58px 32px 48px; border-radius:25px 25px 0 0; }
    }

    @media (max-width: 560px) {
      .container { width:calc(100% - 28px); }
      .section { padding:72px 0; }
      .hero { min-height:880px; }
      .hero__content { padding-top:125px; }
      .hero h1 { font-size:50px; }
      .hero__eyebrow { margin-bottom:22px; }
      .hero__actions { flex-direction:column; align-items:stretch; }
      .btn { width:100%; }
      .hero__image { inset:53% 0 0 0; clip-path:ellipse(110% 75% at 66% 76%); }
      .about__intro { font-size:38px; }
      .about__visual { min-height:430px; }
      .about__badge { left:22px; right:22px; bottom:22px; }
      .product-grid { grid-template-columns:1fr; grid-auto-rows:340px; }
      .product-card__info { left:20px; right:20px; bottom:20px; }
      .product-card__tag { max-width:112px; white-space:normal; text-align:center; line-height:1.35; }
      .product-modal { padding:12px; }
      .product-modal__content { padding:30px 24px; }
      .capabilities__image { min-height:470px; }
      .capabilities__image-main { inset:0 8% 14% 0; }
      .capabilities__image-small { width:54%; height:38%; }
      .capabilities__badge { right:1%; max-width:160px; padding:11px 14px; }
      .metric-grid { gap:12px; }
      .metric strong { font-size:38px; }
      .steps { grid-template-columns:1fr; row-gap:0; }
      .step, .step:not(:first-child), .step:last-child { border-right:0; padding:34px 0 34px; border-bottom:1px solid var(--line); min-height:auto; }
      .step h3 { margin:32px 0 10px; }
      .contact__panel { padding:52px 20px 38px; }
      .contact-form { padding:28px 20px; }
      .form-grid { grid-template-columns:1fr; }
      .form-field--full { grid-column:auto; }
      .form-actions, .news-grid { grid-template-columns:1fr; }
      .news-card:last-child { grid-column:auto; }
      .footer__inner { align-items:flex-start; flex-direction:column; }
      .footer__links { flex-wrap:wrap; }
      .wa { width:54px; height:54px; right:16px; bottom:16px; }
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { animation-duration:.01ms!important; animation-iteration-count:1!important; scroll-behavior:auto!important; transition-duration:.01ms!important; }
      [data-reveal] { opacity:1; transform:none; }
    }
  </style>
</head>
<body>
  @include('website.partials.tracking-body')
  <header class="header" id="header">
    <div class="container nav">
      <a class="brand" href="#home" aria-label="AYAS FOODLINK home">
        <img src="{{ $media['logo'] }}" alt="AYAS FOODLINK logo">
      </a>
      <nav class="nav__links" id="navLinks" aria-label="Primary navigation">
        <a href="#home" data-i18n="navHome">Home</a>
        <a href="#about" data-i18n="navAbout">About</a>
        <a href="#products" data-i18n="navProducts">Products</a>
        <a href="#capabilities" data-i18n="navCapabilities">Capabilities</a>
        <a href="#process" data-i18n="navProcess">How We Work</a>
        <a href="#news" data-i18n="navNews">News</a>
        <a href="#contact" data-i18n="navContact">Contact</a>
      </nav>
      <div class="nav__actions">
        <div class="language-switch" role="group" aria-label="Choose language">
          <button class="language-option" type="button" data-language="id" aria-pressed="false">ID</button>
          <button class="language-option" type="button" data-language="en" aria-pressed="true">EN</button>
        </div>
        <a class="nav__cta" href="https://wa.me/{{ $details['whatsapp'] }}" target="_blank" rel="noopener">
          <span class="nav__dot"></span><span data-i18n="navCta">Start a conversation</span>
        </a>
      </div>
      <button class="menu-btn" id="menuBtn" aria-label="Open navigation" aria-expanded="false"><span></span></button>
    </div>
  </header>

  <main>
    <section class="hero" id="home">
      <div class="hero__image" data-parallax="0.10">
        <img src="{{ $media['hero_image'] }}" alt="Professional food preparation in a commercial kitchen" onerror="this.onerror=null;this.src='{{ asset('assets/images/hero-fallback.jpg') }}'">
      </div>
      <div class="hero__grain"></div>
      <div class="container">
        <div class="hero__content">
          <div class="eyebrow hero__eyebrow" data-i18n="heroEyebrow">Local expertise, global reach.</div>
          <h1 class="display" data-i18n-html="heroTitle">Your <em>Trusted</em> Partner for F&amp;B Supply.</h1>
          <p class="hero__text" data-i18n="heroText">A premium food &amp; beverage supplier for HORECA, catering, and retail sectors across domestic (Indonesia) and export markets.</p>
          <div class="hero__actions">
            <a class="btn btn--gold" href="#products"><span data-i18n="exploreProducts">Explore Products</span>
              <svg class="btn__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <a class="btn btn--outline" href="#contact" data-i18n="discussNeeds">Discuss Your Needs</a>
          </div>
        </div>
      </div>
      <div class="hero__stats">
        <div class="hero__stat"><strong>{{ $details['stat_1_value'] }}</strong><span data-i18n="coreMarket">Core market</span></div>
        <div class="hero__stat"><strong>{{ $details['stat_2_value'] }}</strong><span data-i18n="marketReach">Market reach</span></div>
        <div class="hero__stat"><strong>{{ $details['stat_3_value'] }}</strong><span data-i18n="productLines">Product lines</span></div>
      </div>
    </section>

    <div class="marquee" aria-hidden="true">
      <div class="marquee__track">
        <div class="marquee__group">
          <span class="marquee__item" data-i18n="warmFriendly">We Listen to Your Needs</span><span class="marquee__item" data-i18n="helpfulService">We Deliver the Best Solutions</span><span class="marquee__item" data-i18n="professionalSupport">Professional Support</span><span class="marquee__item" data-i18n="domesticSupply">Domestic Supply</span><span class="marquee__item" data-i18n="exportCapability">Export Capability</span>
        </div>
        <div class="marquee__group">
          <span class="marquee__item" data-i18n="warmFriendly">We Listen to Your Needs</span><span class="marquee__item" data-i18n="helpfulService">We Deliver the Best Solutions</span><span class="marquee__item" data-i18n="professionalSupport">Professional Support</span><span class="marquee__item" data-i18n="domesticSupply">Domestic Supply</span><span class="marquee__item" data-i18n="exportCapability">Export Capability</span>
        </div>
      </div>
    </div>

    <section class="section about" id="about">
      <div class="container">
        <div class="about__head">
          <div class="about__headline" data-reveal="left">
            <div class="eyebrow" style="color:var(--gold)" data-i18n="aboutLabel">Who We Are</div>
            <h2 class="display about__intro" data-i18n-html="aboutTitle">Built to make food sourcing feel <em>simple, dependable, and personal.</em></h2>
          </div>
          <p class="about__copy" data-reveal="right" data-delay="1" data-i18n="aboutCopy">We combine responsive service, carefully selected products, and consistent delivery to help our customers focus on serving their guests and growing their business.</p>
        </div>

        <div class="about__grid">
          <article class="about__visual" data-reveal="left">
            <img src="{{ $media['about_image'] }}" alt="Elegant HORECA restaurant environment" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/images/horeca-fallback.jpg') }}'">
            <div class="about__badge">
              <strong data-i18n="horecaTitle">Made for HORECA</strong>
              <span data-i18n="horecaText">Supporting hotels, restaurants, cafés, catering operations, and other food-service businesses.</span>
            </div>
          </article>
          <div class="values">
            <article class="value-card" data-reveal="right">
              <span class="value-card__num">01</span>
              <svg class="value-card__icon" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M24 42s16-8 16-22V9l-16-5L8 9v11c0 14 16 22 16 22Z"/><path d="m17 23 5 5 10-11"/></svg>
              <h3 data-i18n="warmFriendly">We Listen to Your Needs</h3><p data-i18n="warmText">Careful listening and clear communication help us understand what your business truly needs.</p>
            </article>
            <article class="value-card delay-1" data-reveal="right">
              <span class="value-card__num">02</span>
              <svg class="value-card__icon" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 27v-4a16 16 0 0 1 32 0v4"/><path d="M8 26h6v12H8zM34 26h6v12h-6zM34 39c-2 3-5 4-9 4h-3"/></svg>
              <h3 data-i18n="helpfulService">We Deliver the Best Solutions</h3><p data-i18n="helpfulText">Practical product and supply solutions tailored to your quantity, timing, and market.</p>
            </article>
            <article class="value-card delay-2" data-reveal="right">
              <span class="value-card__num">03</span>
              <svg class="value-card__icon" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.6"><path d="m24 4 18 10v20L24 44 6 34V14L24 4Z"/><path d="m6 14 18 10 18-10M24 24v20"/></svg>
              <h3 data-i18n="professionalSupport">Professional Support</h3><p data-i18n="supportText">Trusted producers and a reliable supply chain.</p>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="section products" id="products">
      <div class="container">
        <div class="products__top">
          <div>
            <div class="eyebrow" style="color:var(--gold-light)" data-reveal data-i18n="productsLabel">Our Product Range</div>
            <h2 class="display section-title" data-reveal data-i18n="productsTitle">Selected products for ambitious food businesses.</h2>
          </div>
          <p data-reveal="right" data-i18n="productsCopy">From locally supplied favourites to export-ready products, our range is selected to deliver quality, convenience, and dependable performance.</p>
        </div>

        <div @class(['product-grid', 'product-grid--extended' => $products->count() > 4])>
          @foreach($products as $index => $product)
            @php
              $fallback = $product->image ? (str_starts_with($product->image, 'assets/') ? asset($product->image) : asset('storage/'.$product->image)) : asset('assets/images/hero-fallback.jpg');
              $primary = $product->image_url ?: $fallback;
              $resolveProductImage = fn ($path) => $path ? (str_starts_with($path, 'assets/') ? asset($path) : asset('storage/'.$path)) : null;
              $galleryImages = array_values(array_filter([$primary, $resolveProductImage($product->gallery_image), $resolveProductImage($product->gallery_image_3), $resolveProductImage($product->gallery_image_4)]));
            @endphp
            <button class="product-card" type="button" data-reveal="{{ $index % 3 === 0 ? 'left' : 'right' }}" data-gallery-images="{{ base64_encode(json_encode($galleryImages, JSON_UNESCAPED_SLASHES)) }}" data-gallery-fallback="{{ $fallback }}" data-description-en="{{ $product->description ?: $product->short_description }}" data-description-id="{{ $product->description_id ?: $product->short_description_id ?: $product->short_description }}" aria-haspopup="dialog" aria-label="View {{ $product->name }} details">
              <img src="{{ $primary }}" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallback }}'">
              <div class="product-card__info"><div><h3 data-content-en="{{ $product->name }}" data-content-id="{{ $product->name_id ?: $product->name }}">{{ $product->name }}</h3><p data-content-en="{{ $product->short_description }}" data-content-id="{{ $product->short_description_id ?: $product->short_description }}">{{ $product->short_description }}</p></div><span class="product-card__tag" data-content-en="{{ $product->market }}" data-content-id="{{ $product->market_id ?: $product->market }}">{{ $product->market }}</span></div>
            </button>
          @endforeach
        </div>
      </div>
    </section>

    <div class="product-modal" id="productModal" hidden>
      <div class="product-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="productModalTitle">
        <button class="product-modal__close" id="productModalClose" type="button" aria-label="Close product details">&times;</button>
        <div class="product-modal__media">
          <div class="product-modal__track" id="productModalTrack" aria-label="Product photo gallery"></div>
          <div class="product-modal__controls" id="productModalControls" aria-label="Photo controls">
            <button class="product-modal__control" id="productModalPrev" type="button" aria-label="Previous photo">&#8592;</button>
            <span class="product-modal__count" id="productModalCount" aria-live="polite">1 / 1</span>
            <button class="product-modal__control" id="productModalNext" type="button" aria-label="Next photo">&#8594;</button>
          </div>
        </div>
        <div class="product-modal__content">
          <span class="product-modal__tag" id="productModalTag"></span>
          <h3 id="productModalTitle"></h3>
          <p id="productModalText"></p>
          <a class="btn btn--gold" id="productModalAction" href="#contact"><span data-i18n="requestProduct">Request Product Information</span>
            <svg class="btn__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>
      </div>
    </div>

    <section class="section capabilities" id="capabilities">
      <div class="container capabilities__grid">
        <div class="capabilities__image" data-reveal="left">
          <div class="capabilities__image-main" data-parallax="0.05"><img src="{{ $media['capability_image_main'] }}" alt="Food supply warehouse and logistics" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/images/warehouse-fallback.jpg') }}'"></div>
          <div class="capabilities__image-small"><img src="{{ $media['capability_image_small'] }}" alt="Restaurant service" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/images/horeca-fallback.jpg') }}'"></div>
          <div class="capabilities__badge"><span data-i18n="capabilityRing">Local expertise · Global reach</span></div>
        </div>
        <div class="capabilities__copy">
          <div class="eyebrow" style="color:var(--gold)" data-reveal data-i18n="capabilitiesLabel">Domestic & Export</div>
          <h2 class="display" data-reveal data-i18n="capabilitiesTitle">Reliable movement from source to destination.</h2>
          <p data-reveal data-i18n="capabilitiesCopy">Our supply capability is designed for businesses that need responsiveness, product consistency, and practical coordination across domestic and export requirements.</p>
          <div class="metric-grid">
            <div class="metric" data-reveal><strong><span class="counter" data-target="{{ $details['metric_1_value'] }}">0</span></strong><span data-i18n="marketChannels">Market channels</span></div>
            <div class="metric delay-1" data-reveal><strong><span class="counter" data-target="{{ $details['metric_2_value'] }}">0</span>+</strong><span data-i18n="coreProductLines">Core product lines</span></div>
            <div class="metric delay-2" data-reveal><strong><span class="counter" data-target="{{ $details['metric_3_value'] }}">0</span></strong><span data-i18n="horecaSegments">HORECA segments</span></div>
            <div class="metric delay-3" data-reveal><strong>{{ $details['metric_4_value'] }}</strong><span data-i18n="responsiveSupport">Responsive support</span></div>
          </div>
          <a class="btn btn--gold" href="#contact"><span data-i18n="requestProduct">Request Product Information</span>
            <svg class="btn__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
        </div>
      </div>
    </section>

    <section class="section process" id="process">
      <div class="container">
        <div class="process__head">
          <div>
            <div class="eyebrow" style="color:var(--gold)" data-reveal data-i18n="processLabel">How We Work</div>
            <h2 class="display section-title" data-reveal data-i18n="processTitle">Simple process. Clear communication.</h2>
          </div>
          <p data-reveal="right" data-i18n="processCopy">A focused supply process designed to make every order easier to understand, coordinate, and deliver.</p>
        </div>
        <div class="steps">
          <article class="step" data-reveal><div class="step__num" data-i18n="step01">Step 01</div><h3 data-i18n="shareNeeds">Share Your Needs</h3><p data-i18n="shareNeedsText">Tell us the product, volume, schedule, and destination required for your operation.</p></article>
          <article class="step delay-1" data-reveal><div class="step__num" data-i18n="step02">Step 02</div><h3 data-i18n="productMatching">Product Matching</h3><p data-i18n="productMatchingText">We recommend suitable products and prepare a clear supply proposal for review.</p></article>
          <article class="step delay-2" data-reveal><div class="step__num" data-i18n="step03">Step 03</div><h3 data-i18n="orderCoordination">Order Coordination</h3><p data-i18n="orderCoordinationText">Our team confirms availability, specifications, documentation, and fulfilment timing.</p></article>
          <article class="step delay-3" data-reveal><div class="step__num" data-i18n="step04">Step 04</div><h3 data-i18n="consistentDelivery">Consistent Delivery</h3><p data-i18n="consistentDeliveryText">Your order is prepared and coordinated for dependable domestic or export delivery.</p></article>
        </div>
      </div>
    </section>

    <section class="section news" id="news">
      <div class="container">
        <div class="news__head">
          <div>
            <div class="eyebrow" style="color:var(--gold-light)" data-reveal data-i18n="newsLabel">News &amp; Activities</div>
            <h2 class="display section-title" data-reveal data-i18n="newsTitle">Updates from our supply network.</h2>
          </div>
          <p data-reveal="right" data-i18n="newsLead">This space is ready for supply progress, export activities, and visits to the partner factories that support AYAS FOODLINK.</p>
        </div>
        <div class="news-grid">
          @foreach($posts as $index => $post)
            @php($postImage = $post->image ? (str_starts_with($post->image, 'assets/') ? asset($post->image) : asset('storage/'.$post->image)) : asset('assets/images/warehouse-fallback.jpg'))
            <a class="news-card {{ $index ? 'delay-'.$index : '' }}" href="{{ route('posts.show', $post) }}" data-reveal>
              <div class="news-card__image"><img src="{{ $postImage }}" alt="{{ $post->title }}" loading="lazy"></div>
              <div class="news-card__body"><span class="news-card__meta" data-i18n="newsComingSoon">Updates coming soon</span><h3 data-content-en="{{ $post->title }}" data-content-id="{{ $post->title_id ?: $post->title }}">{{ $post->title }}</h3><p data-content-en="{{ $post->excerpt }}" data-content-id="{{ $post->excerpt_id ?: $post->excerpt }}">{{ $post->excerpt }}</p><span class="news-card__link" data-i18n="newsReadMore">Read details →</span></div>
            </a>
          @endforeach
        </div>
      </div>
    </section>

    <section class="contact" id="contact">
      <div class="container contact__panel">
        <div class="contact__intro" data-reveal="left">
          <div class="eyebrow" style="color:var(--gold-light)" data-i18n="contactLabel">Contact Us</div>
          <h2 class="display" data-i18n-html="contactTitle">Let’s support your business with <em>better supply.</em></h2>
          <p class="contact__text" data-i18n="contactText">Tell us what your business needs. Our team will respond with suitable product and supply information.</p>
          <div class="contact__details">
            <div class="contact__detail"><span class="contact__icon" aria-hidden="true">⌖</span><div><span class="contact__label" data-i18n="addressLabel">Address</span><div class="contact__value">{{ $details['address'] }}</div></div></div>
            <div class="contact__detail"><span class="contact__icon" aria-hidden="true">☎</span><div><span class="contact__label" data-i18n="phoneLabel">Phone / WhatsApp</span><div class="contact__value"><a href="tel:+{{ $details['whatsapp'] }}">{{ $details['phone'] }}</a></div></div></div>
            <div class="contact__detail"><span class="contact__icon" aria-hidden="true">@</span><div><span class="contact__label" data-i18n="emailLabel">Email</span><div class="contact__value"><a href="mailto:{{ $details['email'] }}">{{ $details['email'] }}</a></div></div></div>
            <div class="contact__detail"><span class="contact__icon" aria-hidden="true">◎</span><div><span class="contact__label">Instagram</span><div class="contact__value"><a href="{{ $details['instagram_url'] }}" target="_blank" rel="noopener">{{ $details['instagram'] }}</a></div></div></div>
            <div class="contact__detail"><span class="contact__icon" aria-hidden="true">◎</span><div><span class="contact__label" data-i18n="websiteLabel">Website</span><div class="contact__value"><a href="#home">{{ $details['website'] }}</a></div></div></div>
          </div>
        </div>

        <form class="contact-form" id="contactForm" action="{{ route('inquiries.store') }}" method="post" data-reveal="right">@csrf
          <h3 data-i18n="formTitle">Request Product Information</h3>
          <p class="contact-form__lead" data-i18n="formLead">Complete the form, then choose WhatsApp or email.</p>
          <div class="form-grid">
            <div class="form-field"><label for="contactName" data-i18n="nameLabel">Name</label><input id="contactName" name="name" type="text" autocomplete="name" required data-i18n-placeholder="namePlaceholder" placeholder="Your name"></div>
            <div class="form-field"><label for="contactCompany" data-i18n="companyLabel">Company</label><input id="contactCompany" name="company" type="text" autocomplete="organization" data-i18n-placeholder="companyPlaceholder" placeholder="Company name"></div>
            <div class="form-field"><label for="contactEmail" data-i18n="emailLabel">Email</label><input id="contactEmail" name="email" type="email" autocomplete="email" required data-i18n-placeholder="emailPlaceholder" placeholder="name@company.com"></div>
            <div class="form-field"><label for="contactPhone" data-i18n="phoneLabel">Phone / WhatsApp</label><input id="contactPhone" name="phone" type="tel" autocomplete="tel" required data-i18n-placeholder="phonePlaceholder" placeholder="Your WhatsApp number"></div>
            <div class="form-field form-field--full"><label for="productInterest" data-i18n="productInterestLabel">Product Interest</label><select id="productInterest" name="product"><option value="" data-i18n="selectProduct">Select a product</option>@foreach($products as $product)<option data-content-en="{{ $product->name }}" data-content-id="{{ $product->name_id ?: $product->name }}">{{ $product->name }}</option>@endforeach<option value="Other" data-i18n="otherProduct">Other product</option></select></div>
            <div class="form-field form-field--full"><label for="contactMessage" data-i18n="messageLabel">Message</label><textarea id="contactMessage" name="message" required data-i18n-placeholder="messagePlaceholder" placeholder="Tell us the product, quantity, location, and timeline you need."></textarea></div>
          </div>
          <div class="form-actions">
            <button class="btn btn--gold" type="submit"><span data-i18n="sendWhatsApp">Send via WhatsApp</span><svg class="btn__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
            <button class="btn btn--email" id="sendEmail" type="button"><span data-i18n="sendEmail">Send via Email</span><svg class="btn__arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16v12H4zM4 7l8 6 8-6"/></svg></button>
          </div>
          <p class="form-note" data-i18n="formNote">Your chosen WhatsApp or email app will open; this website does not store your information.</p>
        </form>
      </div>
    </section>
  </main>

  <footer>
    <div class="container footer__inner">
      <span id="footerCopyright">{{ str_replace('{year}', now()->year, $translations['en']['footerCopyright']) }}</span>
      <div class="footer__links"><a href="#home" data-i18n="footerHome">Home</a><a href="#about" data-i18n="footerAbout">About</a><a href="#products" data-i18n="footerProducts">Products</a><a href="#news" data-i18n="footerNews">News</a><a href="#contact" data-i18n="footerContact">Contact</a></div>
    </div>
  </footer>

  <a class="wa" href="https://wa.me/{{ $details['whatsapp'] }}?text=Hello%20AYAS%20FOODLINK" target="_blank" rel="noopener" aria-label="Contact AYAS FOODLINK through WhatsApp">
    <svg viewBox="0 0 32 32" fill="currentColor" aria-hidden="true"><path d="M16.03 3C8.85 3 3 8.66 3 15.61c0 2.46.74 4.87 2.13 6.91L3 29l6.71-2.09a13.32 13.32 0 0 0 6.31 1.59h.01C23.21 28.5 29 22.84 29 15.89 29 8.94 23.21 3 16.03 3Zm0 23.36a11.1 11.1 0 0 1-5.67-1.55l-.41-.24-3.98 1.24 1.3-3.76-.27-.4a10.5 10.5 0 0 1-1.69-5.72c0-5.81 4.81-10.54 10.73-10.54 2.86 0 5.55 1.09 7.58 3.07a10.36 10.36 0 0 1 3.14 7.46c-.01 5.81-4.82 10.44-10.73 10.44Zm5.89-7.9c-.32-.16-1.91-.92-2.21-1.03-.3-.1-.51-.16-.73.16-.21.31-.83 1.02-1.02 1.23-.19.21-.38.24-.7.08-.32-.16-1.36-.49-2.59-1.56a9.7 9.7 0 0 1-1.79-2.17c-.19-.32-.02-.49.14-.65.15-.14.32-.37.49-.55.16-.18.21-.31.32-.52.11-.21.05-.39-.03-.55-.08-.16-.73-1.72-1-2.35-.26-.63-.53-.54-.73-.55h-.62c-.22 0-.57.08-.86.39-.3.31-1.13 1.08-1.13 2.63s1.16 3.05 1.32 3.26c.16.21 2.28 3.4 5.52 4.77.77.33 1.37.52 1.84.67.77.24 1.47.21 2.03.13.62-.09 1.91-.76 2.18-1.5.27-.73.27-1.36.19-1.5-.08-.13-.3-.21-.62-.37Z"/></svg>
  </a>

  <script>
    (() => {
      const header = document.getElementById('header');
      const menuBtn = document.getElementById('menuBtn');
      const navLinks = document.getElementById('navLinks');
      const setHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 25);
      setHeader();
      window.addEventListener('scroll', setHeader, { passive: true });

      menuBtn.addEventListener('click', () => {
        const open = navLinks.classList.toggle('open');
        menuBtn.classList.toggle('active', open);
        menuBtn.setAttribute('aria-expanded', String(open));
      });
      navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        navLinks.classList.remove('open');
        menuBtn.classList.remove('active');
        menuBtn.setAttribute('aria-expanded', 'false');
      }));

      const languageOptions = [...document.querySelectorAll('.language-option')];
      const translatedText = [...document.querySelectorAll('[data-i18n]')];
      const translatedHtml = [...document.querySelectorAll('[data-i18n-html]')];
      const translatedPlaceholders = [...document.querySelectorAll('[data-i18n-placeholder]')];
      const cmsTranslations = {!! json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
      translatedText.forEach(el => {
        const value = cmsTranslations.en[el.dataset.i18n];
        if (value !== undefined) el.textContent = value;
      });
      translatedHtml.forEach(el => {
        const value = cmsTranslations.en[el.dataset.i18nHtml];
        if (value !== undefined) el.innerHTML = value;
      });
      translatedPlaceholders.forEach(el => {
        const value = cmsTranslations.en[el.dataset.i18nPlaceholder];
        if (value !== undefined) el.placeholder = value;
      });
      const englishText = new Map(translatedText.map(el => [el, el.textContent]));
      const englishHtml = new Map(translatedHtml.map(el => [el, el.innerHTML]));
      const englishPlaceholders = new Map(translatedPlaceholders.map(el => [el, el.placeholder]));
      const id = {
        navHome: 'Beranda', navAbout: 'Tentang Kami', navProducts: 'Produk', navCapabilities: 'Kapabilitas', navProcess: 'Cara Kerja', navNews: 'Berita', navContact: 'Kontak', navCta: 'Mulai Percakapan',
        heroEyebrow: 'Keahlian lokal, jangkauan global.', heroTitle: 'Mitra <em>Terpercaya</em> untuk Pasokan F&amp;B Anda.', heroText: 'Penyedia produk makanan & minuman berkualitas untuk industri HORECA, katering, dan ritel di pasar nasional (Indonesia) maupun ekspor.', exploreProducts: 'Lihat Produk', discussNeeds: 'Diskusikan Kebutuhan', coreMarket: 'Pasar utama', marketReach: 'Jangkauan pasar', productLines: 'Lini produk', scrollDiscover: 'Gulir untuk menjelajah',
        warmFriendly: 'Mendengarkan Kebutuhan Anda', helpfulService: 'Memberikan Solusi Terbaik', professionalSupport: 'Dukungan Profesional', domesticSupply: 'Pasokan Domestik', exportCapability: 'Kapabilitas Ekspor',
        aboutLabel: 'Tentang Kami', aboutTitle: 'Membuat pengadaan pangan terasa <em>mudah, andal, dan personal.</em>', aboutCopy: 'Kami memadukan layanan yang responsif, produk pilihan, dan pengiriman yang konsisten agar pelanggan dapat fokus melayani tamu dan mengembangkan bisnis.', horecaTitle: 'Dibuat untuk HORECA', horecaText: 'Mendukung hotel, restoran, kafe, operasional katering, dan bisnis jasa boga lainnya.', warmText: 'Mendengarkan dengan saksama dan komunikasi yang jelas membantu kami memahami kebutuhan bisnis Anda.', helpfulText: 'Solusi produk dan pasokan yang praktis, disesuaikan dengan jumlah, waktu, dan pasar Anda.', supportText: 'Produsen dan rantai pasok yang handal & dapat dipercaya.',
        productsLabel: 'Rangkaian Produk', productsTitle: 'Produk pilihan untuk bisnis kuliner yang terus berkembang.', productsCopy: 'Dari produk favorit untuk pasokan lokal hingga produk siap ekspor, rangkaian kami dipilih untuk memberikan kualitas, kemudahan, dan performa yang andal.', gelatoText: 'Beragam pilihan rasa untuk pasokan HORECA lokal.', chickenText: 'Empuk, kaya rasa, dan siap disajikan.', brisketText: 'Diasap perlahan dengan cita rasa yang kaya.', tortillaText: 'Fleksibel, praktis, dan siap ekspor.', domesticOnly: 'Khusus Pasar Domestik (Indonesia)', exportOnly: 'Khusus Pasar Ekspor', requestProduct: 'Minta Informasi Produk',
        capabilityRing: 'Keahlian lokal · Jangkauan global', capabilitiesLabel: 'Domestik & Ekspor', capabilitiesTitle: 'Pergerakan andal dari sumber hingga tujuan.', capabilitiesCopy: 'Kapabilitas pasokan kami dirancang untuk bisnis yang membutuhkan respons cepat, konsistensi produk, dan koordinasi praktis untuk kebutuhan domestik maupun ekspor.', marketChannels: 'Saluran pasar', coreProductLines: 'Lini produk utama', horecaSegments: 'Segmen HORECA', responsiveSupport: 'Dukungan responsif',
        processLabel: 'Cara Kerja', processTitle: 'Proses sederhana. Komunikasi jelas.', processCopy: 'Proses pasokan terarah yang membuat setiap pesanan lebih mudah dipahami, dikoordinasikan, dan dikirim.', step01: 'Langkah 01', step02: 'Langkah 02', step03: 'Langkah 03', step04: 'Langkah 04', shareNeeds: 'Sampaikan Kebutuhan', shareNeedsText: 'Sampaikan produk, volume, jadwal, dan tujuan yang dibutuhkan untuk operasional Anda.', productMatching: 'Pencocokan Produk', productMatchingText: 'Kami merekomendasikan produk yang sesuai dan menyiapkan proposal pasokan yang jelas untuk ditinjau.', orderCoordination: 'Koordinasi Pesanan', orderCoordinationText: 'Tim kami mengonfirmasi ketersediaan, spesifikasi, dokumen, dan waktu pemenuhan.', consistentDelivery: 'Pengiriman Konsisten', consistentDeliveryText: 'Pesanan Anda disiapkan dan dikoordinasikan untuk pengiriman domestik atau ekspor yang andal.',
        newsLabel: 'Berita & Kegiatan', newsTitle: 'Kabar dari jaringan pasokan kami.', newsLead: 'Ruang ini disiapkan untuk kabar perkembangan pasokan, kegiatan ekspor, dan kunjungan ke pabrik mitra yang mendukung AYAS FOODLINK.', newsComingSoon: 'Kabar segera hadir', newsSupplyTitle: 'Perkembangan Pasokan', newsSupplyText: 'Perkembangan ketersediaan produk, pemenuhan pesanan, dan distribusi untuk pelanggan kami.', newsExportTitle: 'Kegiatan Ekspor', newsExportText: 'Cerita dan pencapaian dari aktivitas pasokan ekspor yang terus berkembang.', newsFactoryTitle: 'Kunjungan Pabrik Mitra', newsFactoryText: 'Mengenal lebih dekat mitra, fasilitas, dan proses mutu di balik pasokan kami.', newsReadMore: 'Baca selengkapnya →',
        contactLabel: 'Hubungi Kami', contactTitle: 'Mari dukung bisnis Anda dengan <em>pasokan yang lebih baik.</em>', contactText: 'Sampaikan kebutuhan bisnis Anda. Tim kami akan merespons dengan informasi produk dan pasokan yang sesuai.', addressLabel: 'Alamat', phoneLabel: 'Telepon / WhatsApp', websiteLabel: 'Situs Web', formTitle: 'Minta Informasi Produk', formLead: 'Lengkapi formulir, lalu pilih WhatsApp atau email.', nameLabel: 'Nama', companyLabel: 'Perusahaan', productInterestLabel: 'Produk yang Diminati', selectProduct: 'Pilih produk', otherProduct: 'Produk lainnya', messageLabel: 'Pesan', sendWhatsApp: 'Kirim via WhatsApp', sendEmail: 'Kirim via Email', formNote: 'Aplikasi WhatsApp atau email pilihan Anda akan terbuka; situs ini tidak menyimpan informasi Anda.', namePlaceholder: 'Nama Anda', companyPlaceholder: 'Nama perusahaan', phonePlaceholder: 'Nomor WhatsApp Anda', messagePlaceholder: 'Sampaikan produk, jumlah, lokasi, dan waktu yang Anda butuhkan.', rightsReserved: 'Hak cipta dilindungi.'
      };
      Object.assign(id, cmsTranslations.id);

      let currentLanguage = 'en';
      const applyLanguage = language => {
        currentLanguage = language === 'id' ? 'id' : 'en';
        document.documentElement.lang = currentLanguage;
        languageOptions.forEach(option => {
          const active = option.dataset.language === currentLanguage;
          option.classList.toggle('is-active', active);
          option.setAttribute('aria-pressed', String(active));
        });
        translatedText.forEach(el => {
          const value = currentLanguage === 'id' ? id[el.dataset.i18n] : englishText.get(el);
          if (value !== undefined) el.textContent = value;
        });
        translatedHtml.forEach(el => {
          const value = currentLanguage === 'id' ? id[el.dataset.i18nHtml] : englishHtml.get(el);
          if (value !== undefined) el.innerHTML = value;
        });
        translatedPlaceholders.forEach(el => {
          const value = currentLanguage === 'id' ? id[el.dataset.i18nPlaceholder] : englishPlaceholders.get(el);
          if (value !== undefined) el.placeholder = value;
        });
        document.querySelectorAll('[data-content-en]').forEach(el => {
          const value = el.dataset[currentLanguage === 'id' ? 'contentId' : 'contentEn'];
          if (value !== undefined) el.textContent = value;
        });
        const footerTemplate = cmsTranslations[currentLanguage].footerCopyright;
        if (footerTemplate) document.getElementById('footerCopyright').textContent = footerTemplate.replaceAll('{year}', String(new Date().getFullYear()));
        try { localStorage.setItem('ayas-language', currentLanguage); } catch (_) {}
      };
      let preferredLanguage = 'en';
      try { preferredLanguage = localStorage.getItem('ayas-language') || 'en'; } catch (_) {}
      applyLanguage(preferredLanguage);
      languageOptions.forEach(option => option.addEventListener('click', () => applyLanguage(option.dataset.language)));

      const productModal = document.getElementById('productModal');
      const productModalClose = document.getElementById('productModalClose');
      const productModalTrack = document.getElementById('productModalTrack');
      const productModalControls = document.getElementById('productModalControls');
      const productModalPrev = document.getElementById('productModalPrev');
      const productModalNext = document.getElementById('productModalNext');
      const productModalCount = document.getElementById('productModalCount');
      const productModalTitle = document.getElementById('productModalTitle');
      const productModalText = document.getElementById('productModalText');
      const productModalTag = document.getElementById('productModalTag');
      const productModalAction = document.getElementById('productModalAction');
      const productInterest = document.getElementById('productInterest');
      let activeProductCard = null;
      let currentProductSlide = 0;
      let productSlideCount = 1;
      let dragStartX = 0;
      let dragStartScroll = 0;

      const updateProductSlide = index => {
        currentProductSlide = Math.max(0, Math.min(productSlideCount - 1, index));
        productModalCount.textContent = `${currentProductSlide + 1} / ${productSlideCount}`;
      };

      const goToProductSlide = index => {
        const nextIndex = (index + productSlideCount) % productSlideCount;
        productModalTrack.scrollTo({ left: nextIndex * productModalTrack.clientWidth, behavior: 'smooth' });
        updateProductSlide(nextIndex);
      };

      const closeProductModal = restoreFocus => {
        productModal.hidden = true;
        document.body.classList.remove('modal-open');
        if (restoreFocus && activeProductCard) activeProductCard.focus();
      };

      document.querySelectorAll('.product-card').forEach(card => card.addEventListener('click', () => {
        activeProductCard = card;
        const cardImage = card.querySelector('img');
        let galleryImages = [];
        try { galleryImages = JSON.parse(atob(card.dataset.galleryImages)); } catch (_) {}
        galleryImages[0] = cardImage.currentSrc || cardImage.src;
        galleryImages = [...new Set(galleryImages.filter(Boolean))];
        productSlideCount = Math.max(1, galleryImages.length);
        productModalTrack.replaceChildren(...galleryImages.map((source, index) => {
          const slide = document.createElement('div');
          slide.className = 'product-modal__slide';
          const image = document.createElement('img');
          image.src = source;
          image.alt = `${card.querySelector('h3').textContent} - photo ${index + 1}`;
          image.loading = index === 0 ? 'eager' : 'lazy';
          image.addEventListener('error', () => {
            if (image.src !== card.dataset.galleryFallback) image.src = card.dataset.galleryFallback;
          }, { once: true });
          slide.append(image);
          return slide;
        }));
        productModalControls.hidden = productSlideCount < 2;
        productModalTitle.textContent = card.querySelector('h3').textContent;
        productModalText.textContent = card.dataset[currentLanguage === 'id' ? 'descriptionId' : 'descriptionEn'] || card.querySelector('p').textContent;
        productModalTag.textContent = card.querySelector('.product-card__tag').textContent;
        productModal.hidden = false;
        document.body.classList.add('modal-open');
        productModalTrack.scrollLeft = 0;
        updateProductSlide(0);
        productModalClose.focus();
      }));
      productModalPrev.addEventListener('click', () => goToProductSlide(currentProductSlide - 1));
      productModalNext.addEventListener('click', () => goToProductSlide(currentProductSlide + 1));
      productModalTrack.addEventListener('scroll', () => {
        if (!productModalTrack.clientWidth) return;
        updateProductSlide(Math.round(productModalTrack.scrollLeft / productModalTrack.clientWidth));
      }, { passive: true });
      productModalTrack.addEventListener('pointerdown', event => {
        dragStartX = event.clientX;
        dragStartScroll = productModalTrack.scrollLeft;
        productModalTrack.classList.add('is-dragging');
        productModalTrack.setPointerCapture(event.pointerId);
      });
      productModalTrack.addEventListener('pointermove', event => {
        if (!productModalTrack.classList.contains('is-dragging')) return;
        productModalTrack.scrollLeft = dragStartScroll - (event.clientX - dragStartX);
      });
      const finishProductDrag = () => {
        if (!productModalTrack.classList.contains('is-dragging')) return;
        productModalTrack.classList.remove('is-dragging');
        goToProductSlide(Math.round(productModalTrack.scrollLeft / productModalTrack.clientWidth));
      };
      productModalTrack.addEventListener('pointerup', finishProductDrag);
      productModalTrack.addEventListener('pointercancel', finishProductDrag);
      productModalClose.addEventListener('click', () => closeProductModal(true));
      productModal.addEventListener('click', event => {
        if (event.target === productModal) closeProductModal(true);
      });
      document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !productModal.hidden) closeProductModal(true);
        if (event.key === 'ArrowLeft' && !productModal.hidden) goToProductSlide(currentProductSlide - 1);
        if (event.key === 'ArrowRight' && !productModal.hidden) goToProductSlide(currentProductSlide + 1);
      });
      productModalAction.addEventListener('click', () => {
        const option = [...productInterest.options].find(item => item.text === productModalTitle.textContent);
        if (option) productInterest.value = option.value;
        closeProductModal(false);
      });

      const contactForm = document.getElementById('contactForm');
      const buildContactMessage = form => {
        const data = new FormData(form);
        const text = cmsTranslations[currentLanguage];
        return `${text.contactMessageIntro}\n\n${text.nameLabel}: ${data.get('name')}\n${text.companyLabel}: ${data.get('company') || '-'}\n${text.emailLabel}: ${data.get('email')}\n${text.phoneLabel}: ${data.get('phone')}\n${text.productInterestLabel}: ${data.get('product') || '-'}\n${text.messageLabel}: ${data.get('message')}`;
      };
      const trackLead = channel => {
        const eventData = { event_category: 'contact', lead_channel: channel };
        if (typeof window.fbq === 'function') window.fbq('track', 'Lead', { content_name: channel });
        if (typeof window.gtag === 'function') {
          window.gtag('event', 'generate_lead', eventData);
          @if($tracking['google_ads_conversion_id'] && $tracking['google_ads_conversion_label'])
          window.gtag('event', 'conversion', { send_to: @json($tracking['google_ads_conversion_id'].'/'.$tracking['google_ads_conversion_label']) });
          @endif
        } else {
          window.dataLayer = window.dataLayer || [];
          window.dataLayer.push({ event: 'generate_lead', lead_channel: channel });
        }
      };

      contactForm.addEventListener('submit', event => {
        event.preventDefault();
        const form = event.currentTarget;
        if (!form.reportValidity()) return;
        const message = buildContactMessage(form);
        fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json' } }).catch(() => {});
        trackLead('whatsapp');
        const whatsappWindow = window.open(`https://wa.me/{{ $details['whatsapp'] }}?text=${encodeURIComponent(message)}`, '_blank');
        if (whatsappWindow) whatsappWindow.opener = null;
      });

      document.getElementById('sendEmail').addEventListener('click', () => {
        if (!contactForm.reportValidity()) return;
        const subject = cmsTranslations[currentLanguage].contactEmailSubject;
        const message = buildContactMessage(contactForm);
        fetch(contactForm.action, { method: 'POST', body: new FormData(contactForm), headers: { 'Accept': 'application/json' } }).catch(() => {});
        trackLead('email');
        window.location.href = `mailto:{{ $details['email'] }}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
      });

      const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: .13, rootMargin: '0px 0px -30px' });
      document.querySelectorAll('[data-reveal]').forEach(el => revealObserver.observe(el));

      const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          const target = Number(el.dataset.target || 0);
          const duration = 950;
          const start = performance.now();
          const animate = now => {
            const p = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased);
            if (p < 1) requestAnimationFrame(animate);
          };
          requestAnimationFrame(animate);
          observer.unobserve(el);
        });
      }, { threshold: .7 });
      document.querySelectorAll('.counter').forEach(el => counterObserver.observe(el));

      const parallaxItems = [...document.querySelectorAll('[data-parallax]')];
      let ticking = false;
      const parallax = () => {
        const vh = window.innerHeight;
        parallaxItems.forEach(el => {
          const rect = el.getBoundingClientRect();
          if (rect.bottom < 0 || rect.top > vh) return;
          const speed = parseFloat(el.dataset.parallax || 0.06);
          const offset = (rect.top - vh / 2) * speed;
          const img = el.querySelector('img');
          if (img) img.style.transform = `translate3d(0, ${offset}px, 0) scale(1.08)`;
        });
        ticking = false;
      };
      window.addEventListener('scroll', () => {
        if (!ticking && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
          requestAnimationFrame(parallax);
          ticking = true;
        }
      }, { passive: true });

    })();
  </script>
</body>
</html>
