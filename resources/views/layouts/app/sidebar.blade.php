<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased font-sans">
        
        {{-- Envolvemos TODO en un div flex para que el sidebar y el main se alineen correctamente --}}
        <div class="flex min-h-screen">
            
            <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:sidebar.header>
                    <div class="flex items-center gap-3 px-2">
                        <img src="{{ asset('images/logo.webp') }}" alt="Alertics Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold tracking-tight text-blue-900 dark:text-white uppercase">Alertics</span>
                    </div>
                    <flux:sidebar.collapse class="lg:hidden" />
                </flux:sidebar.header>

                <flux:sidebar.nav>
                    <flux:sidebar.group :heading="__('Plataforma')" class="grid">
                        @if(auth()->user()->role === 'admin')
                            <flux:sidebar.item icon="shield-check" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                                Dashboard
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="map" href="#" class="text-white/90 hover:bg-white/10 rounded-xl">
                                Mapa de Alarmas
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="clipboard-document-list" href="#" class="text-white/90 hover:bg-white/10 rounded-xl">
                                Reportes
                            </flux:sidebar.item>

                            <flux:sidebar.item icon="adjustments-horizontal" href="#" class="text-white/90 hover:bg-white/10 rounded-xl">
                                Administración
                            </flux:sidebar.item>

                        @else
                            <flux:sidebar.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                                Mis Reportes
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="plus-circle" :href="route('index')" wire:navigate>
                                Nuevo Reporte
                            </flux:sidebar.item>
                        @endif
                    </flux:sidebar.group>
                </flux:sidebar.nav>

                <flux:spacer />

                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()?->name ?? 'Invitado'" />
            </flux:sidebar>

            {{-- Contenedor para el contenido principal y la cabecera móvil --}}
            <div class="flex-1 flex flex-col">
                <flux:header class="lg:hidden border-b border-zinc-200 dark:border-zinc-700">
                    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                    <flux:spacer />
                    <x-desktop-user-menu :name="auth()->user()?->name ?? 'Invitado'" />
                </flux:header>

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @fluxScripts
    </body>
</html>