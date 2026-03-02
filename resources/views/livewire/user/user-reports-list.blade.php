<?php
use function Livewire\Volt\{computed};
use App\Models\Report;

$myReports = computed(function () {
    return Report::where('user_id', auth()->id())->latest()->paginate(5);
});
?>

<div>
    @volt
    <div class="space-y-4">
        @forelse ($this->myReports as $report)
            <flux:card class="flex items-center justify-between p-4 border-none shadow-sm hover:shadow-md transition-shadow bg-white dark:bg-zinc-900 rounded-2xl">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-blue-50 dark:bg-zinc-800 border border-blue-100 dark:border-zinc-700 flex items-center justify-center font-bold text-blue-900 dark:text-zinc-200 text-xs">
                        #{{ $report->id }}
                    </div>
                    <div>
                        <p class="font-bold text-zinc-800 dark:text-zinc-200 italic">
                            {{ $report->emergencyType->name ?? 'Emergencia' }}
                        </p>
                        <p class="text-[10px] text-zinc-400 uppercase tracking-tighter">{{ $report->created_at->format('d M, Y - H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:badge :color="$report->status === 'pending' ? 'red' : 'green'" size="sm" class="uppercase text-[10px] font-bold tracking-tighter">
                        {{ $report->status === 'pending' ? 'En Proceso' : 'Resuelto' }}
                    </flux:badge>
                    
                    {{-- Enlace directo a Google Maps: Infalible --}}
                    <flux:button 
                        variant="ghost" 
                        size="xs" 
                        icon="map-pin" 
                        href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" 
                        target="_blank"
                        tooltip="Ver Ubicación" 
                    />
                </div>
            </flux:card>
        @empty
            <div class="text-center py-12 bg-zinc-50 dark:bg-zinc-900/50 rounded-3xl border-2 border-dashed border-zinc-200 dark:border-zinc-800">
                <flux:icon.document-text class="mx-auto text-zinc-300 size-12 mb-4" />
                <p class="text-zinc-500 font-medium italic">No hay reportes registrados.</p>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $this->myReports->links() }}
        </div>
    </div>
    @endvolt
</div>