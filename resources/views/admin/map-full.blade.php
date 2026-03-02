<x-layouts::app>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            /* Ajuste de altura para dejar espacio a la leyenda */
            #map { height: calc(100vh - 240px); width: 100%; border-radius: 12px 12px 0 0; z-index: 1; }
            .leaflet-popup-content-wrapper { border-radius: 8px; padding: 5px; }
            .leaflet-popup-content { font-family: sans-serif; }
            
            /* Estilos para la leyenda estilo Figma */
            .map-legend {
                background: white;
                padding: 10px 15px;
                border-radius: 0 0 12px 12px;
                border-top: 1px solid #e5e7eb; /* border-zinc-200 */
                display: flex;
                gap: 20px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #374151; /* text-zinc-700 */
            }
            .dark .map-legend {
                background: #18181b; /* bg-zinc-900 */
                border-top-color: #27272a; /* border-zinc-800 */
                color: #d4d4d8; /* text-zinc-300 */
            }
            .legend-item { display: flex; items-center; gap: 6px; }
            .legend-color { width: 10px; height: 10px; border-radius: 50%; }
        </style>
    @endpush

    <div class="p-6">
        <header class="mb-6 flex justify-between items-center">
            <div>
                <flux:heading size="xl" class="font-bold text-blue-900 dark:text-white uppercase tracking-tight">Mapa Completo de Alarmas</flux:heading>
                <flux:subheading>Visualización geográfica de todas las alarmas de emergencia.</flux:subheading>
            </div>
            <flux:button icon="arrow-path" variant="ghost" onclick="location.reload()">Refrescar Datos</flux:button>
        </header>
        
        <flux:card class="p-0 overflow-hidden shadow-lg border-none bg-white dark:bg-zinc-900">
            {{-- Contenedor del Mapa --}}
            <div id="map"></div>
            
            {{-- Leyenda inferior estilo Figma --}}
            <div class="map-legend">
                <div class="legend-item">
                    <span class="legend-color bg-red-600"></span>
                    <span>Activa (Pendiente)</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color bg-green-600"></span>
                    <span>Resuelta</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color bg-amber-500"></span>
                    <span>En Proceso / Mantenimiento</span>
                </div>
            </div>
        </flux:card>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const reports = @json(\App\Models\Report::all());
                let defaultCenter = [19.2312, -98.2435]; 
                var map = L.map('map').setView(defaultCenter, 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                var markerGroup = new L.featureGroup();

                const getIcon = (status) => {
                    let colorFilter = '';
                    
                    switch(status) {
                        case 'pending': 
                            // Filtro corregido para Rojo Vibrante (Hue-rotate a 135deg y brillo ajustado)
                            colorFilter = 'hue-rotate(135deg) brightness(0.9) saturate(7)';
                            break;
                        case 'resolved': // Verde
                            colorFilter = 'hue-rotate(260deg) brightness(0.8) saturate(5)';
                            break;
                        default: // Amarillo/Naranja
                            colorFilter = 'hue-rotate(180deg) brightness(1) saturate(5)';
                    }

                    return L.divIcon({
                        className: 'custom-marker',
                        html: `<img src="https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png" style="filter: ${colorFilter};">`,
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34]
                    });
                };

                reports.forEach(report => {
                    if (report.latitude && report.longitude && report.latitude != 0) {
                        const marker = L.marker([report.latitude, report.longitude], {
                            icon: getIcon(report.status)
                        });
                        
                        const popupContent = `
                            <div class="p-1 min-w-[150px]">
                                <p class="font-bold text-blue-900 border-b pb-1 mb-1 italic">ALERTICS #${report.id}</p>
                                <p class="text-sm"><b>Estado:</b> <span class="uppercase text-xs font-bold">${report.status}</span></p>
                                <p class="text-sm"><b>Teléfono:</b> ${report.phone}</p>
                                <p class="text-[10px] mt-2 text-zinc-400">${new Date(report.created_at).toLocaleString()}</p>
                            </div>
                        `;
                        
                        marker.bindPopup(popupContent);
                        markerGroup.addLayer(marker);
                    }
                });

                map.addLayer(markerGroup);

                if (reports.length > 0 && markerGroup.getBounds().isValid()) {
                    map.fitBounds(markerGroup.getBounds(), { padding: [50, 50] });
                }
            });
        </script>
    @endpush
</x-layouts::app>