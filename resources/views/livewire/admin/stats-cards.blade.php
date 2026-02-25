<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Calculamos los totales reales de la base de datos
$stats = computed(function () {
    return [
        'activas' => Report::where('status', 'pending')->count(),
        'resueltas' => Report::where('status', 'resolved')->count(),
        'mantenimiento' => 0, // Placeholder para futura implementación
        'hoy' => Report::whereDate('created_at', today())->count(),
    ];
});
?>

<div>
    @volt
    {{-- UN SOLO DIV RAÍZ PARA EVITAR EL ERROR --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <flux:card class="bg-green-50/50 border-green-100 flex flex-col gap-2 dark:bg-green-950/20">
            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <flux:icon.check-circle variant="outline" />
            </div>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['activas'] }}</div>
            <div class="text-sm text-zinc-500 font-medium">Alarmas Activas</div>
        </flux:card>

        <flux:card class="bg-red-50/50 border-red-100 flex flex-col gap-2 dark:bg-red-950/20">
            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <flux:icon.exclamation-circle variant="outline" />
            </div>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['resueltas'] }}</div>
            <div class="text-sm text-zinc-500 font-medium">Alarmas Resueltas</div>
        </flux:card>

        <flux:card class="bg-amber-50/50 border-amber-100 flex flex-col gap-2 dark:bg-amber-950/20">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <flux:icon.wrench variant="outline" />
            </div>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['mantenimiento'] }}</div>
            <div class="text-sm text-zinc-500 font-medium">En Mantenimiento</div>
        </flux:card>

        <flux:card class="bg-blue-50/50 border-blue-100 flex flex-col gap-2 dark:bg-blue-950/20">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <flux:icon.bolt variant="outline" />
            </div>
            <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $this->stats['hoy'] }}</div>
            <div class="text-sm text-zinc-500 font-medium">Activaciones Hoy</div>
        </flux:card>

    </div>
    @endvolt
</div>