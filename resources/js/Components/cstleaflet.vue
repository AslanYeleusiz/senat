<template>
    <div>
        <div id="map" style="height: 700px;"></div>
    </div>
</template>

<script>
    import L from 'leaflet';
    import 'leaflet/dist/leaflet.css';

    export default {
        props: ['markers'],
        mounted() {
            // Инициализация карты
            const map = L.map(this.$el, {
                scrollWheelZoom: false, // 🔒 отключаем скролл
            }).setView([43.2770296, 68.3499297], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);


            // Добавление кастомных маркеров
            const markers = this.markers;

            markers.forEach(markerData => {
                const customIcon = L.icon({
                    iconUrl: markerData.iconUrl,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                });

                L.marker([markerData.lat, markerData.lng], {
                    icon: customIcon
                }).addTo(map).bindPopup(`<div class="row">
                    <div class="col-md-12">
                        <h5>${markerData.name}</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Жоба саны:</strong> ${markerData.projects_count}</li>
                            <li class="list-group-item"><strong>Инвестиция суммасы:</strong> ${markerData.invest_sum.toLocaleString()} ₸</li>
                            <li class="list-group-item"><strong>Жасалған этаптар:</strong> ${markerData.stages_count}</li>
                            <li class="list-group-item"><strong>Біткен этаптар:</strong> ${markerData.active_stages_count}</li>
                        </ul>
                    </div>
                </div>`);
            });
            // Загружаем GeoJSON границ
            fetch('https://polygons.openstreetmap.fr/get_geojson.py?id=215739&params=0')
                .then(response => response.json())
                .then(geojson => {
                    const geoLayer = L.geoJSON(geojson, {
                        style: {
                            color: 'blue',
                            weight: 2,
                            opacity: 0.6,
                            fillOpacity: 0.1
                        }
                    }).addTo(map);

                    map.fitBounds(geoLayer.getBounds());
                })
                .catch(err => console.error('Ошибка загрузки GeoJSON:', err));
        }
    }

</script>
