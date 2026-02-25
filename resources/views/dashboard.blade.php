<x-layouts::app>
    <div class="p-6 lg:p-10 bg-slate-50 min-h-screen dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl space-y-8">
            
            {{-- 1. Encabezado del Dashboard --}}
            <header>
                <flux:heading size="xl" class="font-bold">Dashboard de Monitoreo</flux:heading>
                <flux:subheading>Resumen del estado de las alarmas de emergencia</flux:subheading>
            </header>

            {{-- 2. Espacio para las 4 Cards de Estadísticas (Las haremos en el siguiente paso) --}}
           
            @livewire('admin.stats-cards')

            <div class="my-8">
                 @livewire('admin.dashboard-map-panel')
            </div>
            
            {{-- 3. Lista de Reportes (Tu componente actual) --}}
            <div class="mt-8">
                @livewire('admin.report-list')
            </div>

        </div>
    </div>
</x-layouts::app>