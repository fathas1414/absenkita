<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::withCount('attendances')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai')
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Event::create([
            ...$this->validated($request),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update($this->validated($request));

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    // Buka/tutup sesi absensi
    public function toggle(Event $event): RedirectResponse
    {
        $event->update([
            'status' => $event->isOpen() ? 'tutup' : 'buka',
        ]);

        return back()->with(
            'success',
            $event->isOpen() ? 'Sesi absensi dibuka.' : 'Sesi absensi ditutup.'
        );
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tanggal'       => ['required', 'date'],
            'jam_mulai'     => ['required', 'date_format:H:i'],
            'jam_selesai'   => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);
    }
}