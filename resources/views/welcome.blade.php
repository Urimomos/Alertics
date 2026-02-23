<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alertics - Sistema de Emergencias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-figma-blue { background-color: #1e40af; } 
        .text-figma-blue { color: #1e40af; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-zinc-950 antialiased font-sans">
    
   

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <flux:icon.shield-check class="text-blue-700 w-8 h-8" />
                    <span class="text-2xl font-bold tracking-tight text-blue-900 dark:text-white uppercase">Alertics</span>
                </div>
                
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

    {{-- HERO / FORMULARIO --}}
    <main class="py-12 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            {{-- Columna Izquierda: Texto de Finalidad --}}
            <div class="space-y-6 pt-4">
                <h2 class="text-4xl font-extrabold text-zinc-900 dark:text-white leading-tight">
                    Seguridad ciudadana <br>
                    <span class="text-blue-700">al alcance de un clic.</span>
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    Alertics es una plataforma diseñada para facilitar la comunicación inmediata entre ciudadanos y servicios de emergencia. 
                    Nuestra misión es reducir los tiempos de respuesta mediante la geolocalización precisa y reportes en tiempo real.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex gap-3 items-start">
                        <flux:icon.map-pin class="text-blue-600 mt-1" />
                        <div>
                            <p class="font-bold text-zinc-800 dark:text-zinc-200">Ubicación Precisa</p>
                            <p class="text-sm text-zinc-500">Enviamos tus coordenadas exactas para que la ayuda llegue donde la necesitas.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <flux:icon.clock class="text-blue-600 mt-1" />
                        <div>
                            <p class="font-bold text-zinc-800 dark:text-zinc-200">Respuesta Rápida</p>
                            <p class="text-sm text-zinc-500">Notificaciones instantáneas al centro de mando para una atención sin demoras.</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl dark:bg-blue-900/20 dark:border-blue-800">
                    <p class="text-blue-800 dark:text-blue-300 text-sm italic">
                        "La prevención y el reporte oportuno son la base de una comunidad más segura."
                    </p>
                </div>
            </div>

            {{-- Columna Derecha: Formulario --}}
            <div class="w-full">
                <div class="text-center mb-6 lg:hidden">
                    <flux:heading size="xl">Enviar Alerta</flux:heading>
                </div>
                @livewire('report-form')
            </div>

        </div>
    </main>

    {{-- FOOTER SIMPLE --}}
    <footer class="py-8 border-t border-zinc-200 dark:border-zinc-800 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center text-zinc-500 text-sm">
            &copy; 2026 Alertics - Sistema Integral de Seguridad.
        </div>
    </footer>

    @fluxScripts
</body>
</html>