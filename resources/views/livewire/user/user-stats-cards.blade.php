<?php
use function Livewire\Volt\{state, computed};
use App\Models\Report;

$stats = computed(function () {
    $userId = auth()->id();
    $total = Report::where('user_id', $userId)->count();
    $pendientes = Report::where('user_id', $userId)->where('status', 'pending')->count();
    $resueltos = Report::where('user_id', $userId)->where('status', 'resolved')->count();

    return [
        'total' => $total,
        'pendientes' => $pendientes,
        'resueltos' => $resueltos,
    ];
});
?>

<div> {{-- ESTE ES EL ÚNICO ELEMENTO RAÍZ --}}
    @volt
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total de mis reportes --}}
        <flux:card class="flex items-center gap-4 border-none shadow-lg bg-white dark:bg-zinc-900 rounded-3xl p-6">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600">
                <flux:icon.clipboard-document-list variant="outline" />
            </div>
            <div>
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Mis Reportes</p>
                <p class="text-2xl font-black text-zinc-800 dark:text-white">{{ $this->stats['total'] }}</p>
            </div>
        </flux:card>

        {{-- Mis Pendientes --}}
        <flux:card class="flex items-center gap-4 border-none shadow-lg bg-white dark:bg-zinc-900 rounded-3xl p-6">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-2xl text-red-600">
                <flux:icon.bell-alert variant="outline" />
            </div>
            <div>
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">En Atención</p>
                <p class="text-2xl font-black text-zinc-800 dark:text-white">{{ $this->stats['pendientes'] }}</p>
            </div>
        </flux:card>

        {{-- Mis Resueltos --}}
        <flux:card class="flex items-center gap-4 border-none shadow-lg bg-white dark:bg-zinc-900 rounded-3xl p-6">
            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-2xl text-green-600">
                <flux:icon.check-badge variant="outline" />
            </div>
            <div>
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Finalizados</p>
                <p class="text-2xl font-black text-zinc-800 dark:text-white">{{ $this->stats['resueltos'] }}</p>
            </div>
        </flux:card>
    </div>
    @endvolt
</div>