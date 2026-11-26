@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-stone-900">Halo, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h1>
        <p class="mt-1 text-sm text-stone-500">Kegiatan yang bisa kamu hadir. Tekan hadir saat absensi dibuka.</p>
    </div>

    <div class="space-y-4">
        @forelse ($events as $event)
            @php
                $sudahHadir = in_array($event->id, $hadirEventIds ?? []);
                $dibuka = $event->status === 'buka';
            @endphp

            <article class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3>
                    <h2 class="text-lg font-semibold leading-snug text-stone-900">{{ $event->nama_kegiatan }}</h2>
                    @if ($sudahHadir)
                        <span class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Sudah hadir</span>
                    @elseif ($dibuka)
                        <span class="shrink-0-rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Absensi dibuka</span>
                    @else
                        <span class="shrink-0 rounded-full bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-600">Ditutup</span>
                    @endif
                </div>

                <dl class="mt-3 space-y-1.5 text-sm text-stone-600">
                    <div class="flex gap-2">
                        <dt class="sr-only">Tanggal</dt>
                        <dd>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('l, d F Y') }}@if ($event->jam_mulai), {{ \Carbon\Carbon::parse($event->jam_mulai)->format('H.i') }}@if ($event->jam_selesai) - {{ \Carbon\Carbon::parse($event->jam_selesai)->format('H.i') }}@endif WIB @endif</dd>
                    </div>
                </dl>

                <div class="mt-5">
                    @if ($sudahHadir)
                        <div class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-50 py-3.5 text-sm font-semibold text-emerald-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Kehadiranmu sudah tercatat
                        </div>
                    @elseif ($dibuka)
                        <form method="POST" action="{{ route('attendances.hadir', $event) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-emerald-800 py-3.5 text-base font-semibold text-white transition hover:bg-emerald-700 active:scale-[0.99] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-800">
                                Hadir
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="w-full cursor-not-allowed rounded-xl bg-stone-100 py-3.5 text-sm font-semibold text-stone-400">
                            Absensi belum dibuka
                        </button>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white px-6 py-12 text-center">
                <p class="font-semibold text-stone-800">Belum ada kegiatan</p>
                <p class="mt-1 text-sm text-stone-500">Kegiatan baru dari pengurus akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
@endsection
