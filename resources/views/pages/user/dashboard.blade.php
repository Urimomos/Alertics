<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Filtramos para que el ciudadano solo vea lo reportado con su número
$myReports = computed(fn() => Report::where('phone', auth()->user()->phone)->latest()->get());
?>

<x-layouts::app>
    <div class="min-h-screen bg-slate-50 p-6 dark:bg-zinc-950 lg:p-10">
        <div class="mx-auto max-w-7xl space-y-8">
            
            @volt
            <div id="user-dashboard-container">
                <header class="flex flex-col gap-2">
                    <flux:heading size="xl" class="text-blue-950 dark:text-white">Mis Reportes de Emergencia</flux:heading>
                    <flux:subheading>Monitorea el estado de tus alertas enviadas en tiempo real.</flux:subheading>
                </header>

                {{-- Tarjeta con acento azul de Figma --}}
                <flux:card class="mt-8 border-t-4 border-t-blue-700 shadow-sm">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Fecha</flux:table.column>
                            <flux:table.column>Incidente</flux:table.column>
                            <flux:table.column>Descripción</flux:table.column>
                            <flux:table.column>Estado</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse ($this->myReports as $report)
                                <flux:table.row :key="$report->id">
                                    <flux:table.cell class="text-zinc-600">{{ $report->created_at->locale('es')->isoFormat('DD/MM/YYYY HH:mm') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge size="sm" color="blue" variant="solid">{{ $report->emergencyType->name }}</flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell class="max-w-xs truncate">{{ $report->description }}</flux:table.cell>
                                    <flux:table.cell>
                                        @if($report->status === 'pending')
                                            <flux:badge color="yellow" size="sm" >En Proceso</flux:badge>
                                        @else
                                            <flux:badge color="green" size="sm">Atendido</flux:badge>
                                        @endif
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="4" class="py-12 text-center text-zinc-400 italic">
                                        Aún no has realizado ningún reporte.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            </div>
            @endvolt

        </div>
    </div>
</x-layouts::app>