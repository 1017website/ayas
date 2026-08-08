<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendQontakInquiryReply;
use App\Models\Inquiry;
use App\Services\QontakService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    public function index(Request $request): View
    {
        $inquiries = Inquiry::query()->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($sub) => $sub->where('name', 'like', '%'.$request->q.'%')->orWhere('company', 'like', '%'.$request->q.'%')))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry, QontakService $qontak): View
    {
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }

        return view('admin.inquiries.show', ['inquiry' => $inquiry, 'qontakReady' => $qontak->isReady()]);
    }

    public function update(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $inquiry->update($request->validate(['status' => ['required', 'in:new,read,followed_up']]));

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('success', 'Pesan berhasil dihapus.');
    }

    public function qontak(Inquiry $inquiry, QontakService $qontak): RedirectResponse
    {
        if (! $qontak->isReady()) {
            return back()->withErrors(['qontak' => 'Lengkapi dan aktifkan integrasi Mekari Qontak terlebih dahulu.']);
        }

        $inquiry->update(['qontak_status' => 'queued', 'qontak_error' => null]);
        SendQontakInquiryReply::dispatch($inquiry->id);

        return back()->with('success', 'Pesan Qontak sudah dimasukkan ke antrean pengiriman.');
    }
}
