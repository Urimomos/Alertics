<?php
use function Livewire\Volt\{state, computed};
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Estados para búsqueda y para el formulario de nuevo usuario
state([
    'search' => '',
    'name' => '',
    'email' => '',
    'password' => '',
    'role' => 'user'
]);

$users = computed(fn() => 
    User::query()
        ->where(function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(10)
);

// Función para crear nuevos usuarios/administradores
$saveUser = function () {
    $this->validate([
        'name' => 'required|min:3|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role' => 'required|in:admin,user',
    ], [
        'name.regex' => 'El nombre solo puede contener letras.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    ]);

    User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
        'role' => $this->role,
    ]);

    $this->reset(['name', 'email', 'password', 'role']);
    $this->dispatch('toast', message: 'Usuario creado exitosamente.');
};

$deleteUser = function ($id) {
    $user = User::find($id);
    if ($user && $user->id !== auth()->id()) {
        $user->delete();
        $this->dispatch('toast', message: 'Usuario eliminado correctamente.');
    }
};
?>

<div class="space-y-8 p-6">
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tighter">Gestión de Usuarios</flux:heading>
            <flux:subheading class="text-zinc-500 font-medium italic">Administración de accesos y nuevos administradores.</flux:subheading>
        </div>
        
        <div class="flex flex-wrap gap-2 items-center bg-white dark:bg-zinc-900 p-1.5 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2 px-3">
                <flux:icon.magnifying-glass class="size-4 text-zinc-400" />
                <input wire:model.live="search" type="text" placeholder="Buscar..." class="text-sm border-none bg-transparent focus:ring-0 w-full sm:w-64 dark:text-zinc-300">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Formulario de Registro --}}
        <flux:card class="lg:col-span-1 p-6 h-fit bg-white dark:bg-zinc-900 border-none shadow-xl rounded-3xl">
            <form wire:submit="saveUser" class="space-y-4">
                <flux:heading size="lg" class="font-bold text-zinc-800 dark:text-zinc-200">Registrar Usuario</flux:heading>
                
                <flux:input wire:model="name" label="Nombre Completo" icon="user" placeholder="Ej. Juan Pérez" />
                <flux:input wire:model="email" label="Correo Electrónico" type="email" icon="envelope" placeholder="correo@ejemplo.com" />
                <flux:input wire:model="password" label="Contraseña" type="password" icon="key" placeholder="Mínimo 8 caracteres" />
                
                <flux:select wire:model="role" label="Rol de Usuario">
                    <flux:select.option value="user">Ciudadano</flux:select.option>
                    <flux:select.option value="admin">Administrador</flux:select.option>
                </flux:select>

                <flux:button type="submit" variant="primary" class="w-full bg-blue-700 hover:bg-blue-800 font-bold uppercase text-[13px] tracking-widest">
                    Crear Cuenta
                </flux:button>
            </form>
        </flux:card>

        {{-- Tabla de Usuarios --}}
        <flux:card class="lg:col-span-2 p-6 overflow-hidden border-none shadow-xl bg-white dark:bg-zinc-900 rounded-3xl">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="ps-6 uppercase text-[10px] font-bold text-zinc-400 tracking-widest">Usuario</flux:table.column>
                    <flux:table.column class="uppercase text-[10px] font-bold text-zinc-400 tracking-widest">Rol</flux:table.column>
                    <flux:table.column align="center" class="pe-6 uppercase text-[10px] font-bold text-zinc-400 tracking-widest">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->users as $user)
                        <flux:table.row :key="$user->id" class="hover:bg-zinc-50/50 dark:hover:bg-white/5 transition-colors border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                            <flux:table.cell class="ps-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-800 dark:text-zinc-200 text-sm uppercase">{{ $user->name }}</span>
                                    <span class="text-[10px] text-blue-500 italic">{{ $user->email }}</span>
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                @php $isAdmin = ($user->role === 'admin'); @endphp
                                <flux:badge size="sm" :color="$isAdmin ? 'blue' : 'zinc'" class="uppercase text-[9px] font-black tracking-tighter">
                                    {{ $isAdmin ? 'Admin' : 'Ciudadano' }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell class="pe-6">
                                <div class="flex justify-center">
                                    @if($user->id !== auth()->id())
                                        <flux:button size="xs" variant="ghost" icon="trash" 
                                            wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="¿Estás seguro de eliminar a este usuario?"
                                            class="text-zinc-400 hover:text-red-500" />
                                    @else
                                        <span class="text-[9px] font-bold text-zinc-400 uppercase italic">Tu Sesión</span>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="p-6 bg-zinc-50/30 dark:bg-white/[0.02] border-t border-zinc-100 dark:border-zinc-800">
                {{ $this->users->links() }}
            </div>
        </flux:card>
    </div>
</div>