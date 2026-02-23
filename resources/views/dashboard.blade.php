<x-layouts::app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Llamamos a nuestro nuevo componente de reportes --}}
            @livewire('admin.report-list')
        </div>
    </div>
</x-layouts::app>