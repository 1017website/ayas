<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\QontakService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(QontakService $qontak): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::values(),
            'contentSections' => config('ayas.content_sections'),
            'mediaFields' => config('ayas.media'),
            'detailFields' => config('ayas.details'),
            'seoFields' => config('ayas.seo'),
            'trackingFields' => config('ayas.tracking'),
            'qontakFields' => config('ayas.qontak'),
            'qontakReady' => $qontak->isReady(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => ['required', 'array'],
            'content.*.en' => ['required', 'string', 'max:3000'],
            'content.*.id' => ['required', 'string', 'max:3000'],
            'details' => ['required', 'array'],
            'media.*' => ['nullable', 'image', 'max:5120'],
            'seo' => ['nullable', 'array'],
            'tracking' => ['nullable', 'array'],
            'qontak' => ['nullable', 'array'],
        ]);

        $allowedContent = collect(config('ayas.content_sections'))->flatMap(fn ($fields) => array_keys($fields))->all();
        foreach ($request->input('content', []) as $key => $languages) {
            if (! in_array($key, $allowedContent, true)) {
                continue;
            }
            foreach (['en', 'id'] as $language) {
                Setting::updateOrCreate(['key' => "content_{$key}_{$language}"], ['value' => $languages[$language]]);
            }
        }

        foreach (array_keys(config('ayas.details')) as $key) {
            if ($request->has("details.{$key}")) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input("details.{$key}")]);
            }
        }

        foreach (['seo', 'tracking'] as $group) {
            foreach (array_keys(config("ayas.{$group}")) as $key) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input("{$group}.{$key}", '')]);
            }
        }

        foreach (config('ayas.qontak') as $key => $field) {
            if (($field['type'] ?? null) === 'checkbox') {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->boolean("qontak.{$key}") ? '1' : '0']);

                continue;
            }

            $value = $request->input("qontak.{$key}");
            if (($field['secret'] ?? false) && blank($value)) {
                continue;
            }
            Setting::updateOrCreate(['key' => $key], ['value' => ($field['secret'] ?? false) ? Crypt::encryptString($value) : $value]);
        }

        foreach (array_keys(config('ayas.media')) as $key) {
            if (! $request->hasFile("media.{$key}")) {
                continue;
            }
            $existing = Setting::where('key', "media_{$key}")->value('value');
            if ($existing && ! str_starts_with($existing, 'assets/') && ! str_starts_with($existing, 'http')) {
                Storage::disk('public')->delete($existing);
            }
            $path = $request->file("media.{$key}")->store('website', 'public');
            Setting::updateOrCreate(['key' => "media_{$key}"], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
