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
        $canViewManagementData = request()->user()->isHeadAdmin();

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'posts' => Post::count(),
                'published' => Post::where('is_published', true)->count(),
                'newInquiries' => $canViewManagementData ? Inquiry::where('status', 'new')->count() : null,
                'viewsToday' => $canViewManagementData ? PageView::whereDate('viewed_at', today())->count() : null,
            ],
            'canViewManagementData' => $canViewManagementData,
            'inquiries' => $canViewManagementData ? Inquiry::latest()->take(5)->get() : collect(),
            'posts' => Post::latest()->take(4)->get(),
        ]);
    }
}
