<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. Página de inicio pública (Formulario de reporte)
Route::get('/', function () {
    return view('welcome');
})->name('index'); // Cambiamos el nombre para evitar duplicados

// 2. Ruta de redirección inteligente (El "tráfico")
// Esta es la ruta a la que deben ir todos después de loguearse
Route::get('/home', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Si es admin (Urimomos), va al panel global
    if ($user->role === 'admin') {
        return redirect()->route('dashboard');
    }

    // Si es usuario normal, va a su historial de reportes
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('home');

// 3. Dashboard del Administrador (Protegido por rol)
Route::get('/dashboard', function () {
    if (Auth::user()->role !== 'admin') {
        return redirect()->route('home'); // Si no es admin, lo mandamos al flujo normal
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 4. Dashboard del Usuario Ciudadano
Route::view('my-reports', 'pages.user.dashboard')
    ->middleware(['auth'])
    ->name('user.dashboard');

require __DIR__.'/settings.php';