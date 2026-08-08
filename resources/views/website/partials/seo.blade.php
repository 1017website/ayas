@php
  $isPost = isset($post);
  $pageTitle = $isPost ? ($post->seo_title ?: $post->title.' — '.$details['company_name']) : $details['meta_title'];
  $pageDescription = $isPost ? ($post->seo_description ?: $post->excerpt) : $details['meta_description'];
  $canonical = $isPost ? route('posts.show', $post) : (rtrim($seo['canonical_url'] ?: url('/'), '/').'/' );
  $shareImage = $media['seo_og_image'];
  if ($isPost && $post->image) {
    $shareImage = str_starts_with($post->image, 'assets/') ? asset($post->image) : asset('storage/'.$post->image);
  }
  $organizationSchema = [
    '@context' => 'https://schema.org', '@type' => 'Organization',
    'name' => $details['company_name'], 'url' => rtrim($seo['canonical_url'] ?: url('/'), '/'),
    'logo' => $media['logo'], 'email' => $details['email'], 'telephone' => $details['phone'],
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => $details['address'], 'addressCountry' => 'ID'],
    'sameAs' => array_values(array_filter([$details['instagram_url']])),
  ];
@endphp
<meta name="description" content="{{ $pageDescription }}">
<meta name="robots" content="{{ $seo['robots'] }}">
<link rel="canonical" href="{{ $canonical }}">
@if($seo['google_site_verification'])<meta name="google-site-verification" content="{{ $seo['google_site_verification'] }}">@endif
<meta property="og:type" content="{{ $isPost ? 'article' : 'website' }}">
<meta property="og:site_name" content="{{ $details['company_name'] }}">
<meta property="og:title" content="{{ $isPost ? $pageTitle : ($seo['og_title'] ?: $pageTitle) }}">
<meta property="og:description" content="{{ $isPost ? $pageDescription : ($seo['og_description'] ?: $pageDescription) }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $shareImage }}">
<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $shareImage }}">
<title>{{ $pageTitle }}</title>
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
