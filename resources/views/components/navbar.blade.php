<nav class="bg-white border-b border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Logo y Nombre --}}
            <a href="/" class="flex items-center gap-2 sm:gap-3">
                <img src="{{ asset('images/logo.webp') }}" alt="Alertics Logo" class="h-8 sm:h-10 w-auto">
                <span class="text-xl sm:text-2xl font-bold tracking-tight text-blue-900 dark:text-white uppercase truncate">Alertics</span>
            </a>
            
            <div class="flex items-center gap-2 sm:gap-4">
                @auth
                    {{-- Desktop & Mobile Panel --}}
                    <flux:button href="{{ route('dashboard') }}" variant="ghost" icon="squares-2x2">
                        <span class="hidden sm:inline">Panel</span>
                    </flux:button>
                @else
                    {{-- Desktop Buttons --}}
                    <div class="hidden sm:flex items-center gap-3">
                        <flux:button href="{{ route('login') }}" variant="ghost">Iniciar Sesión</flux:button>
                        <flux:button href="{{ route('register') }}" variant="primary" class="bg-blue-700 hover:bg-blue-800">Registrarse</flux:button>
                    </div>

                    {{-- Mobile Icons (Solo se ven en celular) --}}
                    <div class="flex sm:hidden items-center gap-1">
                        <flux:button href="{{ route('login') }}" variant="ghost" icon="user-circle" square aria-label="Login" />
                        <flux:button href="{{ route('register') }}" variant="primary" class="bg-blue-700" icon="plus" square aria-label="Register" />
                    </div>
                @endauth

                {{-- Opcional: Selector de modo oscuro si Flux lo maneja --}}
                
            </div>
        </div>
    </div>
</nav>