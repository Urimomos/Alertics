<?php
use function Livewire\Volt\{state, computed, mount};
use App\Models\Report;
use App\Models\EmergencyType;

state([
    'reporter_name' => '',
    'phone' => '',
    'emergency_type_id' => 1,
    'description' => '',
    'latitude' => null, 
    'longitude' => null,
]);

mount(function () {
    if (auth()->check()) {
        $this->reporter_name = auth()->user()->name;
        // Si tienes el teléfono en el modelo User, podrías autocompletarlo aquí también:
        $this->phone = auth()->user()->phone; 
    }
});

$types = computed(fn() => EmergencyType::all());

$save = function () {
    $rules = [
        'emergency_type_id' => 'required|exists:emergency_types,id',
        'description' => 'required|min:5',
        'latitude' => 'required', 
        'longitude' => 'required',
    ];

    if (!auth()->check()) {
        // 'regex:/^[\pL\s\-]+$/u' permite letras, espacios y guiones
        $rules['reporter_name'] = 'nullable|regex:/^[\pL\s\-]+$/u|max:50';
        $rules['phone'] = 'required|digits:10';
    }

    $this->validate($rules, [
        'reporter_name.regex' => 'El nombre solo puede contener letras.',
        'phone.required' => 'El teléfono es obligatorio para contactarte.',
        'phone.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
        'latitude.required' => 'Es necesario activar el GPS para enviar la unidad.',
    ]);



    App\Models\Report::create([
        'user_id' => auth()->id(),
        'reporter_name' => auth()->check() ? auth()->user()->name : ($this->reporter_name ?: 'Anónimo'),
        'phone' => auth()->check() ? (auth()->user()->phone ?? $this->phone) : $this->phone,
        'emergency_type_id' => $this->emergency_type_id,
        'description' => $this->description,
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'status' => 'pending',
    ]);

    $this->reset(['reporter_name', 'phone', 'emergency_type_id', 'description', 'latitude', 'longitude']);

    $message = auth()->check() 
        ? '¡Tu reporte ha sido enviado! Te redirigimos a tu panel.' 
        : '¡Alerta enviada con éxito! Las autoridades han sido notificadas.';

    // Despachamos el evento para SweetAlert
    $this->dispatch('report-sent', [
        'title' => '¡Éxito!',
        'text'  => $message,
        'icon'  => 'success',
        'redirect' => auth()->check() ? route('user.dashboard') : null
    ]);

    if (auth()->check()) {
        $this->dispatch('toast', message: '¡Alerta enviada! Revisando historial...');
        return redirect()->route('user.dashboard');
    }

    $this->dispatch('toast', message: '¡Alerta enviada correctamente!');
};
?>

<div>
    <flux:card class="shadow-2xl border-none rounded-3xl p-8">
        <form wire:submit="save" class="space-y-6">
            
            {{-- CAMPOS OCULTOS SI ESTÁ LOGUEADO --}}
            @guest
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input wire:model="reporter_name" 
                    label="Nombre (Opcional)" 
                    icon="user" 
                    placeholder="Tu nombre"
                    onkeypress="return soloLetras(event)" />
                    <flux:input wire:model="phone" 
                    label="Teléfono" 
                    type="tel" 
                    icon="phone" 
                    placeholder="10 dígitos" 
                    maxlength="10"
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                    required />
                </div>
            @endguest

            <div class="space-y-6">
                <flux:select wire:model="emergency_type_id" label="Tipo de Incidente">
                    @foreach($this->types as $type)
                        <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:textarea wire:model="description" label="¿Qué está sucediendo?" placeholder="Describe brevemente la situación..." rows="3" />

                {{-- BOTÓN DE GPS MÁS VISIBLE --}}
                <div class="relative group">
                    @if($latitude)
                        <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border-2 border-green-500 rounded-2xl transition-all">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-500 p-2 rounded-full">
                                    <flux:icon.check class="text-white size-4" />
                                </div>
                                <span class="text-sm font-bold text-green-700 dark:text-green-400 uppercase">Ubicación Capturada</span>
                            </div>
                            <flux:button type="button" size="xs" variant="ghost" onclick="getLocation()" class="text-green-600 underline">Actualizar</flux:button>
                        </div>
                    @else
                        <button type="button" onclick="getLocation()" class="w-full flex items-center justify-center gap-4 p-5 bg-amber-50 dark:bg-amber-900/10 border-2 border-dashed border-amber-400 rounded-2xl hover:bg-amber-100 dark:hover:bg-amber-900/20 transition-all group">
                            <div class="bg-amber-500 p-3 rounded-full shadow-lg group-hover:scale-110 transition-transform">
                                <flux:icon.map-pin class="text-white size-6 animate-bounce" />
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-black text-amber-700 dark:text-amber-400 uppercase tracking-tight">Activar Ubicación GPS</p>
                                <p class="text-[10px] text-amber-600 dark:text-amber-500 uppercase font-bold">Es obligatorio para enviar ayuda</p>
                            </div>
                        </button>
                    @endif
                </div>
            </div>

            <flux:button type="submit" variant="primary" class="w-full py-4 bg-blue-700 hover:bg-blue-800 shadow-lg shadow-blue-200 dark:shadow-none text-lg font-black tracking-widest uppercase rounded-2xl">
                ENVIAR REPORTE AHORA
            </flux:button>
        </form>
    </flux:card>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                const options = { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 };
                navigator.geolocation.getCurrentPosition(
                    (p) => {
                        @this.set('latitude', p.coords.latitude);
                        @this.set('longitude', p.coords.longitude);
                    },
                    (e) => {
                        let mensaje = "Error de GPS: ";
                        switch(e.code) {
                            case e.PERMISSION_DENIED: mensaje += "Permiso denegado."; break;
                            case e.POSITION_UNAVAILABLE: mensaje += "Ubicación no disponible."; break;
                            case e.TIMEOUT: mensaje += "Tiempo agotado."; break;
                        }
                        alert(mensaje);
                    }, 
                    options
                );
            } else {
                alert("Navegador no compatible con GPS.");
            }
        }

        function soloLetras(e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key).toLowerCase();
    let letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
    let especiales = [8, 37, 39, 46]; // Teclas como Backspace, flechas, etc.

    let tecla_especial = false;
    for (let i in especiales) {
        if (key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }

    if (letras.indexOf(tecla) == -1 && !tecla_especial) {
        return false;
    }
    }


            window.addEventListener('report-sent', event => {
            Swal.fire({
                title: event.detail[0].title,
                text: event.detail[0].text,
                icon: event.detail[0].icon,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#18181b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            }).then(() => {
                if (event.detail[0].redirect) {
                    window.location.href = event.detail[0].redirect;
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>