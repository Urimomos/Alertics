<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Datos para las barras de progreso
$reportStats = computed(function () {
    $total = 36; // Referencia del diseño de Figma
    return [
        'total' => $total,
        'activas' => Report::where('status', 'pending')->count(),
        'inactivas' => 8, 
        'mantenimiento' => 5, 
    ];
});
?>

<div>
    @volt
    {{-- UN SOLO DIV RAÍZ QUE CONTIENE EL GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Columna del Mapa (2/3 del espacio) --}}
        <div class="lg:col-span-2">
            <flux:card class="p-0 overflow-hidden h-full flex flex-col">
                <div class="p-6 flex justify-between items-center bg-white dark:bg-zinc-900">
                    <flux:heading size="lg" class="font-bold text-zinc-800 dark:text-white">Mapa de Ubicación de Alarmas</flux:heading>
                    <flux:button variant="primary" class="bg-blue-600 hover:bg-blue-700" icon="map" size="sm">
                        Ver Mapa Completo
                    </flux:button>
                </div>
                
                <div class="flex-1 min-h-[400px] bg-slate-100 dark:bg-zinc-800 relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?..." 
                        class="w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                    
                    {{-- Leyenda flotante como en el diseño original --}}
                    <div class="absolute bottom-4 left-4 flex gap-4 p-3 bg-white/90 dark:bg-zinc-900/90 backdrop-blur rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 text-[10px] font-bold uppercase tracking-wider">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Activa</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Inactiva</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Mantenimiento</span>
                    </div>
                </div>
            </flux:card>
        </div>

        {{-- Columna de Estado (1/3 del espacio) --}}
        <div class="space-y-6">
            <flux:card class="h-full flex flex-col">
                <flux:heading size="lg" class="mb-6 font-bold text-zinc-800 dark:text-white">Estado de Alarmas</flux:heading>
                
                <div class="space-y-6 flex-1">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-500">Total de Alarmas</span>
                        <span class="text-xl font-bold dark:text-white">{{ $this->reportStats['total'] }}</span>
                    </div>

                    {{-- Barras de progreso dinámicas --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <span>Activas</span>
                            <span>{{ $this->reportStats['activas'] }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full rounded-full transition-all duration-500" style="width: {{ ($this->reportStats['activas'] / $this->reportStats['total']) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <span>Inactivas</span>
                            <span>{{ $this->reportStats['inactivas'] }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ ($this->reportStats['inactivas'] / $this->reportStats['total']) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <span>Mantenimiento</span>
                            <span>{{ $this->reportStats['mantenimiento'] }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-500" style="width: {{ ($this->reportStats['mantenimiento'] / $this->reportStats['total']) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <flux:separator class="my-6 text-zinc-100 dark:text-zinc-700" />

                <div class="space-y-4">
                    <flux:heading size="sm" class="uppercase text-xs tracking-widest text-zinc-400">Resumen del Sistema</flux:heading>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-zinc-500">Disponibilidad:</span>
                        <span class="text-green-600 font-bold">92%</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-zinc-500">Última actualización:</span>
                        <span class="text-zinc-400">Hace 2 min</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-zinc-500">Alertas pendientes:</span>
                        <span class="text-red-500 font-bold">3</span>
                    </div>
                </div>
            </flux:card>
        </div>

    </div>
    @endvolt
</div>