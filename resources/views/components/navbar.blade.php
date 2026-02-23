<nav class="bg-white border-b border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.webp') }}" alt="Alertics Logo" class="h-10 w-auto">
                <span class="text-2xl font-bold tracking-tight text-blue-900 dark:text-white uppercase">Alertics</span>
            </a>
            
            <div class="flex items-center gap-4">
                @auth
                    <flux:button href="{{ route('dashboard') }}" variant="ghost">Panel Control</flux:button>
                @else
                    <flux:button href="{{ route('login') }}" variant="ghost">Iniciar Sesión</flux:button>
                    <flux:button href="{{ route('register') }}" variant="primary" class="bg-blue-700 hover:bg-blue-800">Registrarse</flux:button>
                @endauth
            </div>
        </div>
    </div>
</nav>