<?php

namespace App\Http\Controllers;

use App\Jobs\SendQontakInquiryReply;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Services\QontakService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function index(): View
    {
        $settings = Setting::values();

        return view('website.index', [
            'settings' => $settings,
            'translations' => $this->translations($settings),
            'media' => $this->media($settings),
            'details' => $this->details($settings),
            'seo' => $this->group($settings, 'seo'),
            'tracking' => $this->group($settings, 'tracking'),
            'products' => Product::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'posts' => Post::query()->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())->latest('published_at')->take(3)->get(),
        ]);
    }

    public function post(Post $post): View
    {
        abort_unless($post->is_published && $post->published_at?->lte(now()), 404);

        $settings = Setting::values();

        return view('website.post', [
            'post' => $post,
            'settings' => $settings,
            'translations' => $this->translations($settings),
            'media' => $this->media($settings),
            'details' => $this->details($settings),
            'seo' => $this->group($settings, 'seo'),
            'tracking' => $this->group($settings, 'tracking'),
            'otherPosts' => Post::query()->where('is_published', true)->whereKeyNot($post->id)->latest('published_at')->take(3)->get(),
            'relatedPosts' => Post::query()->where('is_published', true)->latest('published_at')->take(3)->get(),
        ]);
    }

    private function translations(array $settings): array
    {
        $translations = ['en' => [], 'id' => []];
        foreach (config('ayas.content_sections') as $fields) {
            foreach ($fields as $key => $field) {
                foreach (['en', 'id'] as $language) {
                    $translations[$language][$key] = $settings["content_{$key}_{$language}"] ?? $field[$language];
                }
            }
        }

        return $translations;
    }

    private function media(array $settings): array
    {
        $media = [];
        foreach (config('ayas.media') as $key => $item) {
            $value = $settings["media_{$key}"] ?? $item['default'];
            $media[$key] = str_starts_with($value, 'http') ? $value : asset(str_starts_with($value, 'assets/') ? $value : 'storage/'.$value);
        }

        return $media;
    }

    private function details(array $settings): array
    {
        return collect(config('ayas.details'))->mapWithKeys(fn ($value, $key) => [$key => $settings[$key] ?? $value])->all();
    }

    private function group(array $settings, string $group): array
    {
        return collect(config("ayas.{$group}"))
            ->mapWithKeys(fn (array $field, string $key) => [$key => $settings[$key] ?? $field['default']])
            ->all();
    }

    public function inquiry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'product' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $inquiry = Inquiry::create($data);

        if (app(QontakService::class)->isReady()) {
            $inquiry->update(['qontak_status' => 'queued']);
            SendQontakInquiryReply::dispatch($inquiry->id)->afterResponse();
        }

        return back()->with('success', 'Terima kasih. Pesan Anda sudah kami terima dan akan segera ditindaklanjuti.');
    }
}
