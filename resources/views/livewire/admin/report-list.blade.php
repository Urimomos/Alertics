<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Obtenemos los reportes ordenados por los más recientes
$reports = computed(fn() => Report::with('emergencyType')->latest()->get());

// Función para cambiar el estatus a "Atendido"
$markAsResolved = function (Report $report) {
    $report->update(['status' => 'resolved']);
};
?>

<div class="space-y-6">
    <flux:heading size="xl" level="1">Gestión de Emergencias</flux:heading>

    <flux:card class="overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Fecha</flux:table.column>
                <flux:table.column>Reportante</flux:table.column>
                <flux:table.column>Incidente</flux:table.column>
                <flux:table.column>Descripción</flux:table.column>
                <flux:table.column>Ubicación</flux:table.column>
                <flux:table.column>Estatus</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->reports as $report)
                    <flux:table.row :key="$report->id">
                        <flux:table.cell class="text-xs text-zinc-500">
                            {{ $report->created_at->format('d/m H:i') }}
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="font-medium">{{ $report->reporter_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $report->phone }}</div>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge size="sm" color="blue">
                                {{ $report->emergencyType->name }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="max-w-xs truncate">
                            {{ $report->description }}
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($report->latitude)
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    icon="map-pin" 
                                    href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" 
                                    target="_blank"
                                >
                                    Ver Mapa
                                </flux:button>
                            @else
                                <span class="text-xs text-zinc-400 italic">Sin GPS</span>
                            @endif
                        </flux:table.cell>

                        <flux:table.cell>
                            @if($report->status === 'pending')
                                <flux:button 
                                    wire:click="markAsResolved({{ $report->id }})" 
                                    size="sm" 
                                    variant="primary" 
                                    class="bg-green-600 hover:bg-green-700"
                                >
                                    Resolver
                                </flux:button>
                            @else
                                <flux:badge color="green" size="sm">Atendido</flux:badge>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>