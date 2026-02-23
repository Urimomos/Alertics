<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alertics - Emergencias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
   
</head>
<body class="bg-zinc-50 dark:bg-zinc-950 antialiased font-sans">
    <div class="flex flex-col items-center justify-center min-h-screen p-6">
        <div class="w-full max-w-lg">
            <header class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-red-600">ALERTICS</h1>
                <p class="text-zinc-500">Reporte ciudadano inmediato</p>
            </header>
            
            {{-- Llamamos al componente Volt de forma segura --}}
            @livewire('report-form')

            <footer class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-zinc-600 underline">Acceso Admin</a>
            </footer>
        </div>
    </div>

    @fluxScripts
</body>
</html>