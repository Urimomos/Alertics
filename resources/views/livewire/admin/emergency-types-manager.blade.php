<?php
use function Livewire\Volt\{state, computed};
use App\Models\EmergencyType;

// Definimos el estado inicial
state(['name' => '', 'editingId' => null]);

// Propiedad computada para obtener los tipos de emergencia
$types = computed(fn() => EmergencyType::latest()->get());

$save = function () {
    $this->validate([
        'name' => 'required|min:3|unique:emergency_types,name,' . ($this->editingId ?? 'NULL'),
    ], [
        'name.required' => 'El nombre es obligatorio.',
        'name.unique' => 'Este tipo de emergencia ya existe.',
    ]);

    if ($this->editingId) {
        EmergencyType::find($this->editingId)->update(['name' => $this->name]);
        $message = "Tipo de emergencia actualizado.";
    } else {
        EmergencyType::create(['name' => $this->name]);
        $message = "Nuevo tipo de emergencia agregado.";
    }

    $this->reset(['name', 'editingId']);
    $this->dispatch('toast', message: $message);
};

$edit = function ($id, $name) {
    $this->editingId = $id;
    $this->name = $name;
};

$delete = function ($id) {
    EmergencyType::find($id)->delete();
    $this->dispatch('toast', message: "Tipo eliminado correctamente.");
};

$cancel = function () {
    $this->reset(['name', 'editingId']);
};
?>

<div class="space-y-8 p-6">
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tighter">Configuración de Emergencias</flux:heading>
            <flux:subheading class="text-zinc-500 font-medium italic">Gestiona las opciones disponibles en el formulario ciudadano.</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Formulario de Registro/Edición --}}
        <flux:card class="lg:col-span-1 p-6 h-fit bg-white dark:bg-zinc-900 border-none shadow-xl rounded-3xl">
            <form wire:submit="save" class="space-y-4">
                <flux:heading size="lg" class="font-bold text-zinc-800 dark:text-zinc-200">
                    {{ $this->editingId ? 'Editar Categoría' : 'Nueva Categoría' }}
                </flux:heading>
                
                <flux:input 
                    wire:model="name" 
                    label="Nombre del Incidente" 
                    placeholder="Ej. Incendio Forestal" 
                    icon="megaphone"
                />

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary" class="flex-1 bg-blue-700 hover:bg-blue-800 font-bold uppercase text-[13px] tracking-widest">
                        {{ $this->editingId ? 'Actualizar' : 'Guardar' }}
                    </flux:button>
                    
                    @if($this->editingId)
                        <flux:button wire:click="cancel" variant="ghost" size="sm">Cancelar</flux:button>
                    @endif
                </div>
            </form>
        </flux:card>

        {{-- Tabla de Opciones --}}
        <flux:card class="lg:col-span-2 p-6  overflow-hidden border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="ps-6 uppercase text-[15px] font-bold text-zinc-400 tracking-widest">Nombre de la Emergencia</flux:table.column>
                    <flux:table.column align="end" class="pe-6 uppercase text-[15px] font-bold text-zinc-400 tracking-widest">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->types as $type)
                        <flux:table.row :key="$type->id" class="hover:bg-zinc-50/50 dark:hover:bg-white/5 transition-colors border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                            <flux:table.cell class="ps-6">
                                <span class="font-bold text-zinc-800 dark:text-zinc-200 text-sm uppercase">{{ $type->name }}</span>
                            </flux:table.cell>

                            <flux:table.cell class="pe-6">
                                <div class="flex justify-end gap-2">
                                    <flux:button size="xs" variant="ghost" icon="pencil-square" 
                                        wire:click="edit({{ $type->id }}, '{{ $type->name }}')" 
                                        class="text-blue-600 hover:bg-blue-50" />
                                    
                                    <flux:button size="xs" variant="ghost" icon="trash" 
                                        wire:click="delete({{ $type->id }})" 
                                        wire:confirm="¿Seguro? Esto podría afectar reportes existentes."
                                        class="text-zinc-400 hover:text-red-500" />
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            
            @if($this->types->isEmpty())
                <div class="p-12 text-center text-zinc-400 italic text-sm">
                    No hay tipos de emergencia registrados.
                </div>
            @endif
        </flux:card>
    </div>
</div>