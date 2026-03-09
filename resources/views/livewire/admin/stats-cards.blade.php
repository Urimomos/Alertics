<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

// Calculamos los totales reales incluyendo el nuevo estado 'processing'
$stats = computed(function () {
    return [
        'activas' => Report::where('status', 'pending')->count(),
        'en_proceso' => Report::where('status', 'processing')->count(),
        'resueltas' => Report::where('status', 'resolved')->count(),
        'hoy' => Report::whereDate('created_at', today())->count(),
    ];
});
?>

<div>
    @volt
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- ALARMAS ACTIVAS - ROJO (Urgencia Máxima) --}}
        <flux:card class="bg-red-50/50 border-red-100 flex flex-col gap-2 dark:bg-red-950/20 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <flux:icon.exclamation-triangle variant="outline" class="animate-pulse" />
            </div>
            <div class="text-3xl font-black text-red-700 dark:text-red-400 tracking-tighter">{{ $this->stats['activas'] }}</div>
            <div class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Alarmas Activas</div>
        </flux:card>

        {{-- EN PROCESO - ÁMBAR (Estado Intermedio) --}}
        <flux:card class="bg-amber-50/50 border-amber-100 flex flex-col gap-2 dark:bg-amber-950/20 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                <flux:icon.wrench variant="outline" />
            </div>
            <div class="text-3xl font-black text-amber-700 dark:text-amber-400 tracking-tighter">{{ $this->stats['en_proceso'] }}</div>
            <div class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">En Atención</div>
        </flux:card>

        {{-- RESUELTAS - VERDE (Finalizado) --}}
        <flux:card class="bg-green-50/50 border-green-100 flex flex-col gap-2 dark:bg-green-950/20 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <flux:icon.check-circle variant="outline" />
            </div>
            <div class="text-3xl font-black text-green-700 dark:text-green-400 tracking-tighter">{{ $this->stats['resueltas'] }}</div>
            <div class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Alarmas Resueltas</div>
        </flux:card>

        {{-- ACTIVACIONES HOY - AZUL (Informativo) --}}
        <flux:card class="bg-blue-50/50 border-blue-100 flex flex-col gap-2 dark:bg-blue-950/20 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <flux:icon.bolt variant="outline" />
            </div>
            <div class="text-3xl font-black text-blue-700 dark:text-blue-400 tracking-tighter">{{ $this->stats['hoy'] }}</div>
            <div class="text-[10px] uppercase font-bold text-zinc-500 tracking-widest">Total Hoy</div>
        </flux:card>

    </div>
    @endvolt
</div>