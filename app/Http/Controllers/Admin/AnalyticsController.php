<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\PageView;
use Carbon\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(): View
    {
        $start = now()->subDays(29)->startOfDay();
        $recent = PageView::query()->where('viewed_at', '>=', $start)->get();
        $byDay = $recent->groupBy(fn (PageView $view) => $view->viewed_at->format('Y-m-d'));
        $chart = collect(range(0, 29))->map(function (int $offset) use ($start, $byDay) {
            $date = $start->copy()->addDays($offset);

            return [
                'date' => $date->format('Y-m-d'),
                'label' => $date->translatedFormat('d M'),
                'views' => $byDay->get($date->format('Y-m-d'), collect())->count(),
                'visitors' => $byDay->get($date->format('Y-m-d'), collect())->pluck('visitor_id')->unique()->count(),
            ];
        });

        return view('admin.analytics.index', [
            'summary' => [
                'today' => PageView::whereDate('viewed_at', today())->count(),
                'views30' => $recent->count(),
                'visitors30' => $recent->pluck('visitor_id')->unique()->count(),
                'leads30' => Inquiry::where('created_at', '>=', $start)->count(),
            ],
            'chart' => $chart,
            'maxViews' => max(1, $chart->max('views')),
            'topPages' => $recent->groupBy('path')->map->count()->sortDesc()->take(8),
            'sources' => $recent->groupBy('source')->map->count()->sortDesc()->take(8),
            'devices' => $recent->groupBy('device')->map->count()->sortDesc(),
            'generatedAt' => Carbon::now(),
        ]);
    }
}
