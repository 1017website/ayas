<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::query()->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))->latest('updated_at')->paginate(12)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? ($data['is_published'] ? now() : null);
        foreach (['image', 'gallery_image_2', 'gallery_image_3'] as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('posts', 'public');
            }
        }
        Post::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita baru berhasil dibuat.');
    }

    public function show(Post $post): RedirectResponse
    {
        return redirect()->route('admin.berita.edit', $post);
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title'], $post->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? ($data['is_published'] ? ($post->published_at ?? now()) : null);
        foreach (['image', 'gallery_image_2', 'gallery_image_3'] as $field) {
            if ($request->hasFile($field)) {
                if ($post->$field && ! str_starts_with($post->$field, 'assets/')) {
                    Storage::disk('public')->delete($post->$field);
                }
                $data[$field] = $request->file($field)->store('posts', 'public');
            }
        }
        $post->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        foreach (['image', 'gallery_image_2', 'gallery_image_3'] as $field) {
            if ($post->$field && ! str_starts_with($post->$field, 'assets/')) {
                Storage::disk('public')->delete($post->$field);
            }
        }
        $post->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'], 'title_id' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'], 'category_id' => ['required', 'string', 'max:100'],
            'excerpt' => ['required', 'string', 'max:500'], 'excerpt_id' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:20000'], 'body_id' => ['required', 'string', 'max:20000'],
            'gallery_caption_1' => ['nullable', 'string', 'max:200'], 'gallery_caption_1_id' => ['nullable', 'string', 'max:200'],
            'gallery_caption_2' => ['nullable', 'string', 'max:200'], 'gallery_caption_2_id' => ['nullable', 'string', 'max:200'],
            'gallery_caption_3' => ['nullable', 'string', 'max:200'], 'gallery_caption_3_id' => ['nullable', 'string', 'max:200'],
            'seo_title' => ['nullable', 'string', 'max:200'], 'seo_description' => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'], 'gallery_image_2' => ['nullable', 'image', 'max:4096'], 'gallery_image_3' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function uniqueSlug(string $title, ?int $ignore = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $number = 2;
        while (Post::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore))->exists()) {
            $slug = $base.'-'.$number++;
        }

        return $slug;
    }
}
