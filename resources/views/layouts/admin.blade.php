<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — LabMS Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'ui-sans-serif'] } } } }</script>
    <style>[x-cloak]{display:none!important}</style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">

<div x-data="{ sidebar: true }" class="flex h-screen overflow-hidden">

    {{-- ── Sidebar ──────────────────────────────────────────────────────────── --}}
    <aside :class="sidebar ? 'w-64' : 'w-16'" class="hidden md:flex flex-col bg-gray-900 transition-all duration-300 ease-in-out flex-shrink-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10 min-h-[65px]">
            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <span x-show="sidebar" x-cloak class="text-white font-bold text-sm leading-tight">LabMS<span class="text-indigo-400 block text-xs font-normal">Super Admin</span></span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 overflow-y-auto space-y-0.5">

            @php
                $navItem = function(string $route, string $label, string $icon, string $match = '') use (&$navItem) {
                    $active = request()->routeIs($match ?: $route . '*');
                    return [$active, $route, $label, $icon];
                };
            @endphp

            {{-- Dashboard --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.dashboard'), 'label' => 'Dashboard', 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'])

            {{-- Users --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.users.index'), 'label' => 'Users', 'active' => request()->routeIs('admin.users*'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'])

            {{-- Labs --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.labs.index'), 'label' => 'Labs', 'active' => request()->routeIs('admin.labs*'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'])

            {{-- Tests Accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.tests*','admin.test-categories*','admin.test-panels*','admin.test-packages*','admin.units*') ? 'true' : 'false' }} }">
                <button @click="open=!open" :class="open ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-colors">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                        <span x-show="sidebar" x-cloak>Tests</span>
                    </span>
                    <svg x-show="sidebar" x-cloak :class="open?'rotate-180':''" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open && sidebar" x-cloak class="ml-4 mt-1 space-y-0.5 border-l border-gray-700 pl-3">
                    <a href="{{ route('admin.tests.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium {{ request()->routeIs('admin.tests*') ? 'text-indigo-400 bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">All Tests</a>
                    <a href="{{ route('admin.test-categories.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium {{ request()->routeIs('admin.test-categories*') ? 'text-indigo-400 bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">Categories</a>
                    <a href="{{ route('admin.test-panels.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium {{ request()->routeIs('admin.test-panels*') ? 'text-indigo-400 bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">Panels</a>
                    <a href="{{ route('admin.test-packages.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium {{ request()->routeIs('admin.test-packages*') ? 'text-indigo-400 bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">Packages</a>
                    <a href="{{ route('admin.units.index') }}" class="block px-3 py-2 rounded-lg text-xs font-medium {{ request()->routeIs('admin.units*') ? 'text-indigo-400 bg-gray-800' : 'text-gray-500 hover:text-white hover:bg-gray-800' }}">Units</a>
                </div>
            </div>

            {{-- Reports --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.reports.index'), 'label' => 'Reports', 'active' => request()->routeIs('admin.reports*'), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'])

            {{-- Subscriptions --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.subscriptions.index'), 'label' => 'Subscriptions', 'active' => request()->routeIs('admin.subscriptions*'), 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'])

            {{-- Plans --}}
            @include('layouts.partials.admin-nav-item', ['href' => route('admin.plans.index'), 'label' => 'Plans', 'active' => request()->routeIs('admin.plans*'), 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'])

        </nav>

        {{-- User Footer --}}
        <div class="border-t border-white/10 p-3">
            <div class="flex items-center gap-3 px-1">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div x-show="sidebar" x-cloak class="min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-gray-500 hover:text-red-400 hover:bg-gray-800 rounded-xl text-xs transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span x-show="sidebar" x-cloak>Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main ─────────────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 flex items-center justify-between px-6 h-16 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebar=!sidebar" class="hidden md:flex p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                        <div class="flex items-center gap-1 text-xs text-gray-400">@yield('breadcrumb')</div>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">Super Admin</span>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error') || $errors->any())
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
                <button @click="show=false" class="ml-auto text-emerald-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
                <button @click="show=false" class="ml-auto text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            @endif
            @if($errors->any())
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button @click="show=false" class="ml-auto text-red-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
