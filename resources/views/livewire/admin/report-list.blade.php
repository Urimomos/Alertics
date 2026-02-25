<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

$reports = computed(fn() => Report::with('emergencyType')->latest()->get());

$markAsResolved = function (Report $report) {
    $report->update(['status' => 'resolved']);
};
?>

<div>
    @volt
    <flux:card class="p-6 overflow-hidden">
        <div class="p-6 flex justify-between items-center">
            <flux:heading size="lg">Reportes Recientes de Activación</flux:heading>
        </div>
        
        <flux:table>
            <flux:table.columns>
                {{-- Agregamos padding a la izquierda (ps-6) para que el ID se mueva a la derecha --}}
                <flux:table.column>ID</flux:table.column>
                <flux:table.column>Ubicación / Reportante</flux:table.column>
                <flux:table.column>Fecha y Hora</flux:table.column>
                <flux:table.column>Estado</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->reports as $report)
                    <flux:table.row :key="$report->id">
                        {{-- Aplicamos el mismo padding ps-6 a la celda de datos --}}
                        <flux:table.cell class="ps-6 font-bold">
                            ALR-{{ str_pad($report->id, 3, '0', STR_PAD_LEFT) }}
                        </flux:table.cell>
                        
                        <flux:table.cell>
                            <div class="font-medium">{{ $report->reporter_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $report->description }}</div>
                        </flux:table.cell>

                        <flux:table.cell>
                            {{ $report->created_at->locale('es')->isoFormat('DD/MM/YYYY HH:mm') }}
                        </flux:table.cell>

                        <flux:table.cell class="pe-6">
                            <flux:badge size="sm" :color="$report->status === 'pending' ? 'amber' : 'green'">
                                {{ $report->status === 'pending' ? 'Activa' : 'Resuelta' }}
                            </flux:badge>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
    @endvolt
</div>