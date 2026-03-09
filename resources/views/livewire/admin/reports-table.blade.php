<?php
use function Livewire\Volt\{state, computed, usesPagination};
use App\Models\Report;

usesPagination();
state(['search' => '', 'filterStatus' => 'all']);

$reports = computed(function () {
    return Report::query()
        ->when($this->search, function ($query) {
            $query->where('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('reporter_name', 'like', '%' . $this->search . '%')
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
        $this->dispatch('toast', message: 'Estado actualizado a ' . $newStatus);
    }
};
?>

<div class="space-y-8">
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tighter">Historial de Reportes</flux:heading>
            <flux:subheading class="text-zinc-500 italic">Gestión integral de alertas ciudadanas.</flux:subheading>
        </div>
        
        <div class="flex flex-wrap gap-2 items-center bg-white dark:bg-zinc-900 p-1.5 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2 px-3">
                <flux:icon.magnifying-glass class="size-4 text-zinc-400" />
                <input wire:model.live="search" type="text" placeholder="Buscar reporte..." class="text-sm border-none bg-transparent focus:ring-0 w-full sm:w-64 dark:text-zinc-300">
            </div>
            <div class="hidden sm:block h-6 w-[1px] bg-zinc-200 dark:bg-zinc-700 mx-2"></div>
            <select wire:model.live="filterStatus" class="text-sm border-none bg-transparent focus:ring-0 pr-8 font-medium text-zinc-600 dark:text-zinc-400 cursor-pointer uppercase text-[10px] font-bold">
                <option value="all">Todos</option>
                <option value="pending">Pendientes</option>
                <option value="processing">En Proceso</option>
                <option value="resolved">Resueltos</option>
            </select>
        </div>
    </div>

    {{-- Tabla Estilo Dashboard --}}
    <flux:card class="overflow-hidden border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
        <flux:table>
            <flux:table.columns>
                <flux:table.column class="ps-6 uppercase text-[10px] font-bold text-zinc-400">ID</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold text-zinc-400">Reportante</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold text-zinc-400">Descripción</flux:table.column>
                <flux:table.column class="uppercase text-[10px] font-bold text-zinc-400">Estado</flux:table.column>
                <flux:table.column align="end" class="pe-6 uppercase text-[10px] font-bold text-zinc-400">Acciones</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->reports as $report)
                    <flux:table.row :key="$report->id" class="hover:bg-zinc-50/50 dark:hover:bg-white/5 transition-colors border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <flux:table.cell class="ps-6">
                            <span class="font-bold text-blue-600 dark:text-zinc-500 text-sm">#{{ $report->id }}</span>
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="flex flex-col">
                                <span class="font-bold text-zinc-800 dark:text-zinc-200 text-xs uppercase">{{ $report->reporter_name ?? 'Anónimo' }}</span>
                                <span class="text-[10px] text-blue-500 font-medium font-mono">{{ $report->phone }}</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex flex-col max-w-xs md:max-w-md">
                                <span class="text-xs text-zinc-600 dark:text-zinc-400 line-clamp-1 italic">"{{ $report->description }}"</span>
                                <span class="text-[9px] text-zinc-400 mt-0.5">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($report->status === 'pending')
                                <div class="flex items-center gap-2 bg-red-500/10 px-3 py-1 rounded-full border border-red-500/20 w-fit">
                                    <span class="relative flex h-1.5 w-1.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-600"></span>
                                    </span>
                                    <span class="text-[9px] font-bold text-red-600 uppercase tracking-wider">Activa</span>
                                </div>
                            @elseif($report->status === 'processing')
                                <div class="flex items-center gap-2 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20 w-fit">
                                    <flux:icon.wrench class="size-3 text-amber-600 animate-pulse" />
                                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">En Proceso</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 bg-green-500/10 px-3 py-1 rounded-full border border-green-500/20 w-fit">
                                    <flux:icon.check-circle variant="solid" class="size-3 text-green-500" />
                                    <span class="text-[9px] font-bold text-green-500 uppercase tracking-wider">Atendida</span>
                                </div>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell class="pe-6">
                            <div class="flex justify-end items-center gap-2">
                                {{-- Ubicación GPS --}}
                                <flux:button size="xs" variant="ghost" icon="map-pin" 
                                    href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" 
                                    target="_blank" 
                                    class="text-zinc-400 hover:text-blue-500" tooltip="Ver GPS" />

                                {{-- Botón: Mover a "En Proceso" (Solo si está pendiente) --}}
                                @if($report->status === 'pending')
                                    <flux:button size="xs" variant="subtle" icon="wrench" 
                                        wire:click="updateStatus({{ $report->id }}, 'processing')" 
                                        class="text-amber-600 hover:bg-amber-50" tooltip="Iniciar Atención" />
                                @endif

                                {{-- Botón: Resolver (Si está pendiente o en proceso) --}}
                                @if($report->status !== 'resolved')
                                    <flux:button size="xs" variant="filled" icon="check" 
                                        wire:click="updateStatus({{ $report->id }}, 'resolved')" 
                                        class="bg-green-600 hover:bg-green-700 text-white shadow-sm" tooltip="Finalizar" />
                                @endif

                                {{-- Botón: Reiniciar (Si ya no está pendiente) --}}
                                @if($report->status !== 'pending')
                                    <flux:button size="xs" variant="ghost" icon="arrow-path" 
                                        wire:click="updateStatus({{ $report->id }}, 'pending')" 
                                        class="text-zinc-400 hover:text-red-500" tooltip="Reactivar Alerta" />
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
        
        <div class="p-6 bg-zinc-50/30 dark:bg-white/[0.02] border-t border-zinc-100 dark:border-zinc-800">
            {{ $this->reports->links() }}
        </div>
    </flux:card>
</div>