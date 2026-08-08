@php
  $baseGoogleTag = $tracking['google_tag_id'] ?: ($tracking['ga4_measurement_id'] ?: $tracking['google_ads_conversion_id']);
@endphp
@if($tracking['gtm_container_id'])
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+encodeURIComponent(i)+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@json($tracking['gtm_container_id']));</script>
@endif
@if($baseGoogleTag)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($baseGoogleTag) }}"></script>
<script>
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());
@if($tracking['ga4_measurement_id'])gtag('config',@json($tracking['ga4_measurement_id']));@endif
@if($tracking['google_tag_id'])gtag('config',@json($tracking['google_tag_id']));@endif
@if($tracking['google_ads_conversion_id'])gtag('config',@json($tracking['google_ads_conversion_id']));@endif
</script>
@endif
@if($tracking['meta_pixel_id'])
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',@json($tracking['meta_pixel_id']));fbq('track','PageView');</script>
@endif
