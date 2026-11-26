<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width-device-width, iniitial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Beranda') - Absen Kita</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
  @vite(['resources/css/app/css', 'resources/js/app/js'])
  <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-stone-50 text-stone-800 antialiased">
@php
    $isAdminArea = request()->routeIS('admin.*');
    $linkBase = 'flex items-center gap-3 rounderd-lg px-3 py-2.5 text-sm font-medium transition';
@endphp

<div x-data="{ open: false }" class="min-h-screen">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-30 border-b border-emerald-950/20 bg-emerald-900 text-white">
      <div class="flex h-14 items-center justify-between px-4 sm:px-6">
          <div class=:flex items-center gap-3">
              @if ($isAdminArea)
                  <button type="button" @click="open = true" class="-ml-1 rounded-md p-2 hover:bg-emerald-800 lg:hidden" aria-label="Buka menu">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                  </button>
              @endif
              <a href="{{ $isAdminArea ? route('admin.events.index') : route('dashboard') }}" class="text-lg font-bold tracking-tight">Absen Kita</a>
          </div>

          <div class="flex items-center gap-3 text-sm">
              <span class="hidden max-w-[10rem] truncate text-emerald-100 sm:inline">{{ auth()->user()->name }}</span>
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="rounded-lg border border-emerald-600 px-3 py-1.5 font-medium hover:bg-emerald-800">Keluar</button>
              </form>
          </div>
      </div>
    </header>

    {{-- SIDEBAR (khusus admin) --}}
    @if ($isAdminArea)
        <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full border-r border-stone-200 bg-white pt-14 transition-transform lg:z-20 lg:translate-x-0"
            :class="{ 'translate-x-0': open }">
            <div class="absolute inset-x-0 top-0 flex h-14 items-center justify-between px-4 lg:hidden">
                <span class="font-bold text-emerald-900">Menu admin</span>
                <button type="button" @click="open = false" class="rounded-md p-2 hover:bg-stone-100" aria-label="Tutup menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>
            <nav class="space-y-1 p-3 pt-16 lg:pt-3">
                <a href="{{ route{'admin.events.index') }}"
                    class="{{ $linkBase }} {{ request()->routeIs('admin.events.*', 'admin.attendances.*') ? 'bg-emerald-50 text-emerald-900' : 'text-stone-600 hover:bg-stone-100' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    Kegiatan
                </a>
                @if (Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}"
                        class="{{ $linkBase }} {{ request()->routeIs('profile.*') ? 'bg-emerald-50 text-emerald-900' : 'text-stone-600 hover:bg-stone-100' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0"/></svg>
                        Profil saya
                    </a>
                @endif
            </nav>
        </aside>
    @endif

    {{-- KONTEN --}}
    <main class="{{ $isAdminArea ? 'lg:pl-64' : '' }}:>
        <div class="mx-aitp w-full {{ $isAdminArea ? 'max-w-5xl' : 'max-w-2xl' }} px-4 py-6 sm:px-6">

          @if (session('succes'))
              <div role="status" class="mb-5 flex items-start gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                  <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox= 0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                  <span> {{ session('success') }}</span>
              </div>
          @endif
          @if (session('error'))
              <div role="alert" class="mb-5 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                  <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d=<12 8v4m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/></svg>
                  <span>{{ session('error') }}</span>
              </div>
          @endif

          @yield('content')
        </div>
    </main>           
</div>

@stack('scripts')
</body>
</html>
