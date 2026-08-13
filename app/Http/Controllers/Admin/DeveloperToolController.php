<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class DeveloperToolController extends Controller
{
    private const TOOLS = [
        'migrate' => ['command' => 'migrate', 'parameters' => ['--force' => true], 'success' => 'Migrasi database selesai dijalankan.'],
        'optimize-clear' => ['command' => 'optimize:clear', 'parameters' => [], 'success' => 'Seluruh cache optimasi berhasil dibersihkan.'],
        'storage-link' => ['command' => 'storage:link', 'parameters' => [], 'success' => 'Tautan storage berhasil dibuat atau sudah tersedia.'],
    ];

    public function index(): View
    {
        return view('admin.developer.index', [
            'storageLinked' => file_exists(public_path('storage')),
        ]);
    }

    public function run(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tool' => ['required', Rule::in(array_keys(self::TOOLS))],
        ]);

        $lock = Cache::lock('ayas-developer-tool', 30);
        if (! $lock->get()) {
            return back()->withErrors(['tool' => 'Proses developer lain masih berjalan. Silakan tunggu sebentar.']);
        }

        try {
            $tool = self::TOOLS[$data['tool']];
            $exitCode = Artisan::call($tool['command'], $tool['parameters']);

            if ($exitCode !== 0) {
                return back()->withErrors(['tool' => 'Perintah gagal dijalankan. Periksa konfigurasi server lalu coba kembali.']);
            }

            return back()->with('success', $tool['success']);
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['tool' => 'Perintah tidak dapat diselesaikan pada server ini.']);
        } finally {
            $lock->release();
        }
    }
}
