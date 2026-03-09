<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Limitamos la consulta a los últimos 5 para que el Dashboard cargue rápido
$reports = computed(fn() => Report::with('emergencyType')
    ->latest()
    ->take(5)
    ->get()
);

// Función para actualizar estado rápidamente desde el dashboard si es necesario
$updateStatus = function (Report $report, $status) {
    $report->update(['status' => $status]);
    $this->dispatch('toast', message: 'Estado actualizado.');
};
?>

<div>
    @volt
    <flux:card class="p-6 overflow-hidden border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
        <div class="p-6 flex justify-between items-center border-b border-zinc-100 dark:border-zinc-800">
            <div>
                <flux:heading size="lg" class="font-bold text-blue-900 dark:text-white uppercase tracking-tight">Reportes Recientes</flux:heading>
                <flux:subheading class="text-[10px] uppercase font-medium text-zinc-400">Últimas 5 activaciones del sistema</flux:subheading>
            </div>
            {{-- Badge decorativo para indicar "En Vivo" --}}
            <div class="flex items-center gap-2 px-3 py-1 bg-green-50 dark:bg-green-900/20 rounded-full">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-[10px] font-bold text-green-700 uppercase">Live</span>
            </div>
        </div>
        
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="ps-6">ID</flux:table.column>
                <flux:table.column>Reportante / Descripción</flux:table.column>
                <flux:table.column>Fecha y Hora</flux:table.column>
                <flux:table.column class="pe-6 text-center">Estado</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->reports as $report)
                    <flux:table.row :key="$report->id" class="hover:bg-zinc-50/50 dark:hover:bg-white/5 transition-colors">
                        <flux:table.cell class="ps-6 font-bold text-blue-800 dark:text-zinc-500 text-xs">
                            ALR-{{ str_pad($report->id, 3, '0', STR_PAD_LEFT) }}
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-800 dark:text-zinc-200 text-xs uppercase">{{ $report->reporter_name ?? 'Anónimo' }}</span>
                                <span class="text-[10px] text-zinc-500 line-clamp-1">{{ $report->description }}</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell class="text-xs text-zinc-600 dark:text-zinc-400">
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </flux:table.cell>

                        <flux:table.cell class="pe-6">
                            <div class="flex justify-left">
                                @if($report->status === 'pending')
                                    <flux:badge size="sm" color="red" class="uppercase text-[9px] font-black tracking-tighter">Activa</flux:badge>
                                @elseif($report->status === 'processing')
                                    <flux:badge size="sm" color="amber" class="uppercase text-[9px] font-black tracking-tighter">En Proceso</flux:badge>
                                @else
                                    <flux:badge size="sm" color="green" class="uppercase text-[9px] font-black tracking-tighter">Atendida</flux:badge>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center py-10 text-zinc-400 italic">
                            No hay reportes recientes para mostrar.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        {{-- BOTÓN PARA VER TODO EL HISTORIAL --}}
        <div class="p-4 bg-zinc-50/50 dark:bg-white/[0.02] border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
            <flux:button :href="route('admin.reports')" variant="ghost" icon-trailing="chevron-right" size="sm" class="text-blue-700 dark:text-blue-400 font-bold uppercase text-[10px] tracking-widest hover:bg-blue-50" wire:navigate>
                Ver historial completo de reportes
            </flux:button>
        </div>
    </flux:card>
    @endvolt
</div>