<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alertics - Acceso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="bg-zinc-50 dark:bg-zinc-950 antialiased font-sans">
    {{-- Tu nueva Navbar con el logo --}}
    <x-navbar />

    <div class="min-h-[80vh] flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden sm:rounded-2xl border border-zinc-100 dark:border-zinc-800">
            {{ $slot }}
        </div>
    </div>

    {{-- Tu nuevo Footer con los datos de contacto --}}
    <x-footer />

    @fluxScripts
</body>
</html>