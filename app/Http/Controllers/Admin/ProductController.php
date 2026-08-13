<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))->orderBy('sort_order')->orderBy('name')->paginate(12)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        foreach ($this->galleryFields() as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('products', 'public');
            }
        }
        Product::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.produk.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'assets/')) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        foreach ($this->galleryFields() as $field) {
            if ($request->hasFile($field)) {
                $this->deleteStoredImage($product->$field);
                $data[$field] = $request->file($field)->store('products', 'public');
            }
        }
        $product->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! str_starts_with($product->image, 'assets/')) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($this->galleryFields() as $field) {
            $this->deleteStoredImage($product->$field);
        }
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'], 'name_id' => ['required', 'string', 'max:150'],
            'market' => ['required', 'string', 'max:100'], 'market_id' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:400'], 'short_description_id' => ['required', 'string', 'max:400'],
            'description' => ['nullable', 'string', 'max:5000'], 'description_id' => ['nullable', 'string', 'max:5000'],
            'image_url' => ['nullable', 'url', 'max:1000'], 'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'image' => ['nullable', 'image', 'max:4096'],
            'gallery_image' => ['nullable', 'image', 'max:4096'],
            'gallery_image_3' => ['nullable', 'image', 'max:4096'],
            'gallery_image_4' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function galleryFields(): array
    {
        return ['gallery_image', 'gallery_image_3', 'gallery_image_4'];
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'assets/')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $name, ?int $ignore = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $number = 2;
        while (Product::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore))->exists()) {
            $slug = $base.'-'.$number++;
        }

        return $slug;
    }
}
