<footer class="bg-zinc-900 text-zinc-300 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- Info --}}
        <div>
            <div class="flex items-center gap-2 mb-4 text-white">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-8 w-auto">
                <span class="text-xl font-bold uppercase">Alertics</span>
            </div>
            <p class="text-sm">Sistema integral de reporte ciudadano para emergencias en tiempo real.</p>
        </div>

        {{-- Contacto (Pon tus datos aquí) --}}
        <div>
            <h4 class="text-white font-bold mb-4">Contacto de Emergencia</h4>
            <ul class="space-y-2 text-sm">
                <li class="flex items-center gap-2">
                    <flux:icon.phone size="sm" /> +52 (555) 000-0000
                </li>
                <li class="flex items-center gap-2">
                    <flux:icon.envelope size="sm" /> contacto@alertics.com
                </li>
            </ul>
        </div>

        {{-- Finalidad --}}
        <div>
            <h4 class="text-white font-bold mb-4">Ubicación Central</h4>
            <p class="text-sm">Atención las 24 horas, los 7 días de la semana para una comunidad más segura.</p>
        </div>
    </div>
    <div class="border-t border-zinc-800 mt-10 pt-6 text-center text-xs">
        &copy; 2026 Alertics. Todos los derechos reservados.
    </div>
</footer>