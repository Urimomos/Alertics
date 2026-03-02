<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// 1. Lógica para obtener estadísticas reales y el último reporte
$dashboardData = computed(function () {
    $total = Report::count() ?: 1; // Evitamos división por cero
    $activas = Report::where('status', 'pending')->count();
    $resueltas = Report::where('status', 'resolved')->count();
    $ultimo = Report::latest()->first();

    return [
        'total' => Report::count(),
        'activas' => $activas,
        'resueltas' => $resueltas,
        'mantenimiento' => 0, // Placeholder para futura lógica
        'porcentaje_activas' => ($activas / $total) * 100,
        'porcentaje_resueltas' => ($resueltas / $total) * 100,
        'ultimo_reporte' => $ultimo,
    ];
});
?>

<div>
    @volt
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <flux:card class="p-0 overflow-hidden h-full flex flex-col">
                <div class="p-6 flex justify-between items-center bg-white dark:bg-zinc-900">
                    <flux:heading size="lg" class="font-bold">Última Ubicación de Alerta</flux:heading>
                    
                    <div class="flex gap-2">
                        {{-- BOTÓN DE REFRESCO MANUAL --}}
                        <flux:button variant="ghost" icon="arrow-path" size="sm" onclick="loadDashboardMap()" tooltip="Recargar mapa" />
                        
                        <flux:button variant="primary" :href="route('admin.map')" class="bg-blue-600" icon="map" size="sm" wire:navigate>
                            Ver Mapa Completo
                        </flux:button>
                    </div>
                </div>
                
                <div class="flex-1 min-h-[400px] bg-slate-100 dark:bg-zinc-800 relative">
                    <div id="mini-map" class="w-full h-full"></div>
                    
                    @if($this->dashboardData['ultimo_reporte'])
                        <div class="absolute bottom-4 left-4 p-3 bg-white/90 dark:bg-zinc-900/90 backdrop-blur rounded-xl shadow-md border border-zinc-200 dark:border-zinc-700 z-[1000]">
                            <p class="text-[10px] font-bold uppercase text-blue-700">Reporte #{{ $this->dashboardData['ultimo_reporte']->id }}</p>
                            <p class="text-xs text-zinc-600 dark:text-zinc-300">Tel: {{ $this->dashboardData['ultimo_reporte']->phone }}</p>
                        </div>
                    @endif
                </div>
            </flux:card>
        </div>
        {{-- Columna de Estado (Gráficas con datos Reales) --}}
        <div class="space-y-6">
            <flux:card class="h-full flex flex-col">
                <flux:heading size="lg" class="mb-6 font-bold text-zinc-800 dark:text-white">Estado de Alarmas</flux:heading>
                
                <div class="space-y-6 flex-1">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-zinc-500">Total de Alarmas</span>
                        <span class="text-xl font-bold dark:text-white">{{ $this->dashboardData['total'] }}</span>
                    </div>

                    {{-- Barra: Activas (Rojo) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <span>Pendientes (Emergencia)</span>
                            <span>{{ $this->dashboardData['activas'] }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full transition-all duration-700" style="width: {{ $this->dashboardData['porcentaje_activas'] }}%"></div>
                        </div>
                    </div>

                    {{-- Barra: Resueltas (Verde) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs font-bold text-zinc-700 dark:text-zinc-300">
                            <span>Resueltas</span>
                            <span>{{ $this->dashboardData['resueltas'] }}</span>
                        </div>
                        <div class="w-full h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full transition-all duration-700" style="width: {{ $this->dashboardData['porcentaje_resueltas'] }}%"></div>
                        </div>
                    </div>
                </div>

                <flux:separator class="my-6" />

                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Disponibilidad del Sistema:</span>
                        <span class="text-green-600 font-bold">100%</span>
                    </div>
                    <div class="flex justify-center italic text-zinc-400 text-[10px]">
                        Datos actualizados en tiempo real
                    </div>
                </div>
            </flux:card>
        </div>
    </div>

    {{-- Script para el Mini Mapa --}}
   {{-- Script para el Mini Mapa corregido para Livewire --}}
   @push('scripts')
    <script>
        function loadDashboardMap() {
            const mapContainer = document.getElementById('mini-map');
            if (!mapContainer) return;

            // Si ya hay un mapa, lo removemos para evitar el error de "Map already initialized"
            if (mapContainer._leaflet_id) {
                mapContainer._leaflet_id = null;
                mapContainer.innerHTML = ""; 
            }

            const ultimo = @json($this->dashboardData['ultimo_reporte']);
            const coords = (ultimo && ultimo.latitude && ultimo.longitude) 
                ? [ultimo.latitude, ultimo.longitude] 
                : [19.2312, -98.2435];
            
            try {
                var miniMap = L.map('mini-map').setView(coords, ultimo ? 16 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMap);

                if (ultimo) {
                    L.marker(coords).addTo(miniMap).bindPopup(`Último Reporte #${ultimo.id}`).openPopup();
                }

                setTimeout(() => { miniMap.invalidateSize(); }, 200);
            } catch (e) {
                console.error("Error al cargar el mapa:", e);
            }
        }

        // Eventos de carga
        document.addEventListener('livewire:navigated', loadDashboardMap);
        document.addEventListener('DOMContentLoaded', loadDashboardMap);
    </script>
    @endpush
    @endvolt
</div>