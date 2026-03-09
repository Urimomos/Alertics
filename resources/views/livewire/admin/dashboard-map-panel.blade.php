<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// 1. Lógica para obtener estadísticas reales incluyendo el nuevo estado 'processing'
$dashboardData = computed(function () {
    $total = Report::count() ?: 1; // Evitamos división por cero
    $activas = Report::where('status', 'pending')->count();
    $proceso = Report::where('status', 'processing')->count();
    $resueltas = Report::where('status', 'resolved')->count();
    
    // Obtenemos el último reporte de forma segura
    $ultimo = Report::latest()->first();

    return [
        'total' => Report::count(),
        'activas' => $activas,
        'proceso' => $proceso,
        'resueltas' => $resueltas,
        'porcentaje_activas' => ($activas / $total) * 100,
        'porcentaje_proceso' => ($proceso / $total) * 100,
        'porcentaje_resueltas' => ($resueltas / $total) * 100,
        'ultimo_reporte' => $ultimo, // Aseguramos que esta llave siempre exista
    ];
});
?>

<div>
    @volt
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Columna del Mapa --}}
        <div class="lg:col-span-2">
            <flux:card class="p-0 overflow-hidden h-full flex flex-col border-none shadow-xl rounded-3xl bg-white dark:bg-zinc-900">
                <div class="p-6 flex justify-between items-center">
                    <flux:heading size="lg" class="font-black uppercase tracking-tighter text-zinc-800 dark:text-white">Última Ubicación de Alerta</flux:heading>
                    
                    <div class="flex gap-2">
                        <flux:button variant="ghost" icon="arrow-path" size="sm" onclick="loadDashboardMap()" tooltip="Recargar mapa" />
                        
                        <flux:button variant="primary" :href="route('admin.map')" class="bg-blue-600 font-bold text-[10px] uppercase tracking-widest" icon="map" size="sm" wire:navigate>
                            Ver Mapa Completo
                        </flux:button>
                    </div>
                </div>
                
                <div class="flex-1 min-h-[400px] bg-slate-100 dark:bg-zinc-800 relative">
                    <div id="mini-map" class="w-full h-full z-0"></div>
                    
                    @if($this->dashboardData['ultimo_reporte'])
                        <div class="absolute bottom-4 left-4 p-4 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md rounded-2xl shadow-2xl border border-zinc-200 dark:border-zinc-700 z-[1000]">
                            <div class="flex items-center gap-3">
                                <div class="size-2 bg-red-500 rounded-full animate-ping"></div>
                                <p class="text-[10px] font-black uppercase text-red-600 tracking-widest">Alerta Reciente #{{ $this->dashboardData['ultimo_reporte']->id }}</p>
                            </div>
                            <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200 mt-1">Tel: {{ $this->dashboardData['ultimo_reporte']->phone }}</p>
                            <p class="text-[9px] text-zinc-500 uppercase font-medium">{{ $this->dashboardData['ultimo_reporte']->created_at->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </flux:card>
        </div>

        {{-- Columna de Estado (Gráficas Actualizadas) --}}
        <div class="space-y-6">
            <flux:card class="h-full flex flex-col border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
                <flux:heading size="lg" class="mb-6 font-black text-zinc-800 dark:text-white uppercase tracking-tighter">Estado de Alarmas</flux:heading>
                
                <div class="space-y-6 flex-1">
                    <div class="flex justify-between items-center bg-zinc-50 dark:bg-white/5 p-4 rounded-2xl">
                        <span class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Total de Alertas</span>
                        <span class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $this->dashboardData['total'] }}</span>
                    </div>

                    {{-- Barra: Activas (Rojo) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase text-red-600">
                            <span>Pendientes</span>
                            <span>{{ $this->dashboardData['activas'] }}</span>
                        </div>
                        <div class="w-full h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-red-500 h-full transition-all duration-1000" style="width: {{ $this->dashboardData['porcentaje_activas'] }}%"></div>
                        </div>
                    </div>

                    {{-- Barra: En Proceso (Ámbar) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase text-amber-600">
                            <span>En Proceso</span>
                            <span>{{ $this->dashboardData['proceso'] }}</span>
                        </div>
                        <div class="w-full h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full transition-all duration-1000" style="width: {{ $this->dashboardData['porcentaje_proceso'] }}%"></div>
                        </div>
                    </div>

                    {{-- Barra: Resueltas (Verde) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase text-green-600">
                            <span>Resueltas</span>
                            <span>{{ $this->dashboardData['resueltas'] }}</span>
                        </div>
                        <div class="w-full h-2 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full transition-all duration-1000" style="width: {{ $this->dashboardData['porcentaje_resueltas'] }}%"></div>
                        </div>
                    </div>
                </div>

                <flux:separator class="my-6" />

                <div class="space-y-4 text-xs font-medium">
                    <div class="flex justify-between items-center">
                        <span class="text-zinc-500">Disponibilidad:</span>
                        <span class="text-green-600 font-black uppercase tracking-tighter">100% Operativo</span>
                    </div>
                    <div class="flex justify-center italic text-zinc-400 text-[9px]">
                        Actualización automática activada
                    </div>
                </div>
            </flux:card>
        </div>
    </div>

    {{-- Script del Mapa --}}
    @push('scripts')
    <script>
        function loadDashboardMap() {
            const mapContainer = document.getElementById('mini-map');
            if (!mapContainer) return;

            if (mapContainer._leaflet_id) {
                mapContainer.innerHTML = ""; 
                delete mapContainer._leaflet_id;
            }

            const data = @json($this->dashboardData['ultimo_reporte']);
            const coords = (data && data.latitude && data.longitude) 
                ? [data.latitude, data.longitude] 
                : [19.2312, -98.2435];
            
            try {
                var miniMap = L.map('mini-map', { zoomControl: false }).setView(coords, data ? 16 : 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMap);

                if (data) {
                    L.marker(coords).addTo(miniMap)
                        .bindPopup(`<b class="uppercase text-xs">Alerta #${data.id}</b><br><span class="text-xs">${data.phone}</span>`)
                        .openPopup();
                }

                setTimeout(() => { miniMap.invalidateSize(); }, 300);
            } catch (e) { console.error("Error Leaflet:", e); }
        }

        document.addEventListener('livewire:navigated', loadDashboardMap);
        document.addEventListener('DOMContentLoaded', loadDashboardMap);
    </script>
    @endpush
    @endvolt
</div>