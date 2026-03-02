<x-layouts::app>
    <div class="p-6 space-y-8">
        {{-- Encabezado de Bienvenida --}}
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tighter italic">Mi Panel de Seguridad</flux:heading>
                <flux:subheading class="italic">Bienvenido a Alertics. Aquí puedes ver el estado de tus reportes.</flux:subheading>
            </div>
            {{-- Botón de acción rápida --}}
            <flux:button href="{{ route('index') }}" variant="primary" class="bg-blue-700 hover:bg-blue-800" icon="plus-circle" wire:navigate>
                Nuevo Reporte
            </flux:button>
        </header>

        {{-- Estadísticas Personales --}}
        <livewire:user.user-stats-cards />

        {{-- Historial Personal de Reportes --}}
        <div class="space-y-4">
            <flux:heading size="lg" class="font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-widest text-[10px]">Mi Historial Reciente</flux:heading>
            <livewire:user.user-reports-list />
        </div>
    </div>
</x-layouts::app>