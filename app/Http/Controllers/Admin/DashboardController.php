<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\PageView;
use App\Models\Post;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'posts' => Post::count(),
                'newInquiries' => Inquiry::where('status', 'new')->count(),
                'published' => Post::where('is_published', true)->count(),
                'viewsToday' => PageView::whereDate('viewed_at', today())->count(),
            ],
            'inquiries' => Inquiry::latest()->take(5)->get(),
            'posts' => Post::latest()->take(4)->get(),
        ]);
    }
}
