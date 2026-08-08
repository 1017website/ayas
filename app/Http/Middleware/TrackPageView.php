<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $agent = (string) $request->userAgent();

        if ($response->getStatusCode() >= 400 || $this->isBot($agent) || $request->header('DNT') === '1') {
            return $response;
        }

        $visitorId = $request->cookie('ayas_visitor');
        if (! is_string($visitorId) || ! Str::isUuid($visitorId)) {
            $visitorId = (string) Str::uuid();
            $response->headers->setCookie(cookie('ayas_visitor', $visitorId, 60 * 24 * 365, '/', null, $request->isSecure(), true, false, 'Lax'));
        }

        $referrer = $request->headers->get('referer');
        $utmSource = $request->query('utm_source');

        PageView::create([
            'visitor_id' => $visitorId,
            'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            'path' => '/'.ltrim($request->path(), '/'),
            'route_name' => $request->route()?->getName(),
            'referrer' => $referrer,
            'source' => $utmSource ?: $this->sourceFromReferrer($referrer, $request->getHost()),
            'medium' => $request->query('utm_medium'),
            'campaign' => $request->query('utm_campaign'),
            'device' => $this->device($agent),
            'browser' => $this->browser($agent),
            'ip_hash' => $request->ip() ? hash_hmac('sha256', $request->ip(), (string) config('app.key')) : null,
            'viewed_at' => now(),
        ]);

        return $response;
    }

    private function isBot(string $agent): bool
    {
        return $agent === '' || preg_match('/bot|crawl|spider|slurp|headless|lighthouse|preview/i', $agent) === 1;
    }

    private function sourceFromReferrer(?string $referrer, string $host): string
    {
        $referrerHost = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;
        if (! $referrerHost || $referrerHost === $host) {
            return 'Direct';
        }

        return match (true) {
            str_contains($referrerHost, 'google.') => 'Google',
            str_contains($referrerHost, 'facebook.com') => 'Facebook',
            str_contains($referrerHost, 'instagram.com') => 'Instagram',
            str_contains($referrerHost, 'linkedin.com') => 'LinkedIn',
            str_contains($referrerHost, 't.co'), str_contains($referrerHost, 'twitter.com'), str_contains($referrerHost, 'x.com') => 'X / Twitter',
            default => $referrerHost,
        };
    }

    private function device(string $agent): string
    {
        return match (true) {
            preg_match('/tablet|ipad/i', $agent) === 1 => 'Tablet',
            preg_match('/mobile|iphone|android/i', $agent) === 1 => 'Mobile',
            default => 'Desktop',
        };
    }

    private function browser(string $agent): string
    {
        return match (true) {
            str_contains($agent, 'Edg/') => 'Edge',
            str_contains($agent, 'Chrome/') => 'Chrome',
            str_contains($agent, 'Firefox/') => 'Firefox',
            str_contains($agent, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }
}
