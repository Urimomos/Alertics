<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased font-sans">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-8 w-auto">
                <span class="text-xl font-bold tracking-tight text-blue-900 dark:text-white uppercase">Alertics</span>
            </div>

            <flux:navbar class="-mb-px max-lg:hidden ml-6">
                @if(auth()->user()->role === 'admin')
                    <flux:navbar.item icon="shield-check" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>Dashboard</flux:navbar.item>
                @else
                    <flux:navbar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>Mis Reportes</flux:navbar.item>
                @endif
            </flux:navbar>

            <flux:spacer />
            <x-desktop-user-menu />
        </flux:header>

        {{-- Mobile Sidebar (para cuando el header está activo) --}}
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-6 w-auto">
                <flux:sidebar.collapse />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" :href="route('home')">Mis Reportes</flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        <main>{{ $slot }}</main>
        @fluxScripts
    </body>
</html>