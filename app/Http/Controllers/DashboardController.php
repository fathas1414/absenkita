<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.events.index');
        }

        // Kegiatan yang sesi absensinya sedang dibuka
        $events = Event::where('status', 'buka')
            ->orderBy('jam_mulai')
            ->get();

        // ID kegiatan yang sudah diabsen user ini
        $hadirEventIds = $user->attendances()->pluck('event_id')->all();

        return view('anggota.dashboard', compact('events', 'hadirEventIds'));
    }
}