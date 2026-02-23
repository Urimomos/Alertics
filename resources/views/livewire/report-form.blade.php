<?php
use function Livewire\Volt\{state, computed};
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

$types = computed(fn() => EmergencyType::all());

    $save = function () {
        // 1. Validación de los campos
        $this->validate([
            'phone' => 'required|min:10',
            'emergency_type_id' => 'required|exists:emergency_types,id',
            'description' => 'required|min:5',
            'latitude' => 'required', // Si quieres probar sin GPS en PC, cámbialo a 'nullable'
            'longitude' => 'required', // Si quieres probar sin GPS en PC, cámbialo a 'nullable'
        ], [
            'phone.required' => 'El teléfono es obligatorio para contactarte.',
            'latitude.required' => 'Es necesario obtener tu ubicación GPS.',
        ]);

        // 2. Creación del reporte en la base de datos
        // Asegúrate de que estos campos estén en el $fillable de tu modelo Report
        App\Models\Report::create([
            'reporter_name' => $this->reporter_name ?: 'Anónimo',
            'phone' => $this->phone,
            'emergency_type_id' => $this->emergency_type_id,
            'description' => $this->description,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => 'pending', // Estado inicial del reporte
        ]);

        // 3. Limpiar el formulario después de guardar
        $this->reset([
            'reporter_name', 
            'phone', 
            'emergency_type_id', 
            'description', 
            'latitude', 
            'longitude'
        ]);

        // 4. Notificar al usuario (Requiere <flux:toast /> en welcome.blade.php)
        $this->dispatch('toast', message: '¡Alerta enviada correctamente!');
    };
?>

<div>
    <flux:card>
        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="reporter_name" label="Nombre (Opcional)" icon="user" />
            <flux:input wire:model="phone" label="Teléfono" type="tel" icon="phone" required />

            <flux:select wire:model="emergency_type_id" label="Incidente">
                @foreach($this->types as $type)
                    <flux:select.option value="{{ $type->id }}">{{ $type->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:textarea wire:model="description" label="Descripción" />

            <div class="p-4 border rounded-lg bg-white dark:bg-zinc-900 flex justify-between items-center">
                <flux:label>GPS</flux:label>
                @if($latitude)
                    <flux:badge color="green">Localizado</flux:badge>
                @else
                    <flux:button type="button" size="sm" variant="subtle" onclick="getLocation()">Activar GPS</flux:button>
                @endif
            </div>

            <flux:button type="submit" variant="primary" class="w-full py-3">ENVIAR REPORTE</flux:button>
        </form>
    </flux:card>

    <script>
        function getLocation() {
    if (navigator.geolocation) {
        // Añadimos opciones para mayor precisión
        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(
            (p) => {
                @this.set('latitude', p.coords.latitude);
                @this.set('longitude', p.coords.longitude);
                alert("Ubicación capturada correctamente.");
            },
            (e) => {
                // Esto te dirá exactamente por qué falla
                let mensaje = "";
                switch(e.code) {
                    case e.PERMISSION_DENIED: mensaje = "Permiso denegado por el usuario."; break;
                    case e.POSITION_UNAVAILABLE: mensaje = "Ubicación no disponible."; break;
                    case e.TIMEOUT: mensaje = "Tiempo de espera agotado."; break;
                }
                alert("Error de GPS: " + mensaje);
            }, 
            options
        );
    } else {
        alert("Tu navegador no soporta geolocalización.");
    }
}
    </script>
</div>