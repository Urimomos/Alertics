<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

state(['search' => '', 'filterStatus' => 'all']);

$reports = computed(function () {
    return Report::query()
        ->when($this->search, function ($query) {
            $query->where('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
        })
        ->when($this->filterStatus !== 'all', function ($query) {
            $query->where('status', $this->filterStatus);
        })
        ->latest()
        ->paginate(10);
});

$updateStatus = function ($id, $newStatus) {
    $report = Report::find($id);
    if ($report) {
        $report->update(['status' => $newStatus]);
    }
};
?>

<div class="space-y-8">
    {{-- Encabezado Estilo Dashboard --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tighter italic">Historial de Reportes</flux:heading>
            <flux:subheading class="text-zinc-500 italic">Gestión de alertas ciudadanas para Acuamanala.</flux:subheading>
        </div>
        
        {{-- Contenedor de Filtros con Tailwind Puro (Cero Errores) --}}
        <div class="flex flex-wrap gap-2 items-center bg-white dark:bg-zinc-900 p-1.5 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2 px-3">
                <flux:icon.magnifying-glass class="size-4 text-zinc-400" />
                <input wire:model.live="search" type="text" placeholder="Buscar reporte..." class="text-sm border-none bg-transparent focus:ring-0 w-full sm:w-48 dark:text-zinc-300">
            </div>
            
            <div class="hidden sm:block h-6 w-[1px] bg-zinc-200 dark:bg-zinc-700 mx-2"></div>
            
            <select wire:model.live="filterStatus" class="text-sm border-none bg-transparent focus:ring-0 pr-8 font-medium text-zinc-600 dark:text-zinc-400 cursor-pointer">
                <option value="all">Todos los estados</option>
                <option value="pending">Pendientes</option>
                <option value="resolved">Resueltos</option>
            </select>
        </div>
    </div>

    {{-- Tabla Estilo Dashboard --}}
    <flux:card class="p-8 overflow-hidden border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="pl-6 uppercase text-[10px] font-bold tracking-widest text-zinc-400">ID</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold tracking-widest text-zinc-400">Contacto</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold tracking-widest text-zinc-400">Registro</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold tracking-widest text-zinc-400">Estado</flux:table.column>
                <flux:table.column align="end" class="pr-6 uppercase text-[10px] font-bold tracking-widest text-zinc-400">Acción</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->reports as $report)
                    <flux:table.row :key="$report->id" class="hover:bg-zinc-50/50 dark:hover:bg-white/5 transition-colors border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <flux:table.cell class="pl-6">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-blue-50 dark:bg-zinc-800 text-blue-700 dark:text-zinc-200 font-bold text-xs border border-blue-100 dark:border-zinc-700">
                                #{{ $report->id }}
                            </span>
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $report->phone }}</span>
                                <span class="text-[9px] text-zinc-400 uppercase font-bold tracking-tight">Móvil Registrado</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $report->created_at->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-zinc-400">{{ $report->created_at->format('H:i') }} hrs</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($report->status === 'pending')
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                    </span>
                                    <span class="text-[11px] font-bold text-red-600 uppercase tracking-tighter">Activa</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-green-600">
                                    <flux:icon.check-circle variant="solid" class="size-3.5" />
                                    <span class="text-[11px] font-bold uppercase tracking-tighter text-green-700">Atendida</span>
                                </div>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="pr-6">
                            <div class="flex justify-end gap-1">
                                @if($report->status === 'pending')
                                    <flux:button size="xs" variant="ghost" icon="check" wire:click="updateStatus({{ $report->id }}, 'resolved')" class="text-green-600 hover:text-green-700" tooltip="Marcar Resuelto" />
                                @endif
                                <flux:button size="xs" variant="ghost" icon="map-pin" :href="route('admin.map')" tooltip="Ver Ubicación" />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        <div class="p-6 bg-zinc-50/30 dark:bg-white/5 border-t border-zinc-100 dark:border-zinc-800">
            {{ $this->reports->links() }}
        </div>
    </flux:card>
</div>