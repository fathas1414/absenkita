<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    // Anggota menekan tombol "Hadir"
    public function hadir(Request $request, Event $event): RedirectResponse
    {
        // 1. Sesi harus sedang dibuka
        if (! $event->isOpen()) {
            return back()->with('error', 'Sesi absensi untuk kegiatan ini sedang ditutup.');
        }

        // 2 & 3. Simpan; kalau sudah pernah absen, ambil data yang lama
        $attendance = Attendance::createOrFirst(
            ['user_id' => $request->user()->id, 'event_id' => $event->id],
            ['status' => 'hadir'],
        );

        if (! $attendance->wasRecentlyCreated) {
            return back()->with('error', 'Kamu sudah tercatat hadir di kegiatan ini.');
        }

        return back()->with(
            'success',
            'Kehadiran dicatat pukul ' . $attendance->created_at->format('H:i') . '.'
        );
    }

    // Admin melihat daftar hadir satu kegiatan
    public function index(Event $event): View
    {
        $attendances = $event->attendances()
            ->with('user')
            ->oldest()
            ->get();

        return view('admin.attendances.index', compact('event', 'attendances'));
    }

    // TODO (SAK): method export() untuk Laravel-Excel
}