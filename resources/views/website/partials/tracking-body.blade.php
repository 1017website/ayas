@if($tracking['gtm_container_id'])
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ urlencode($tracking['gtm_container_id']) }}" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
@endif
@if($tracking['meta_pixel_id'])
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ urlencode($tracking['meta_pixel_id']) }}&ev=PageView&noscript=1" alt=""></noscript>
@endif
