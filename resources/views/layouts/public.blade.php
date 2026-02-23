<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Alertics - Emergencias</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased font-sans">
        {{ $slot }}

        @fluxScripts
    </body>
</html>