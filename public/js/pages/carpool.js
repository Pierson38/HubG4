document.addEventListener("DOMContentLoaded", () => {
    console.log(coordonates)

    var map = L.map('map').setView([coordonates.to.lat, coordonates.to.lng], 13);
    var titleLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var from = L.marker([coordonates.from.lat, coordonates.from.lng]).addTo(map);
    var to = L.marker([coordonates.to.lat, coordonates.to.lng]).addTo(map);
    L.Routing.control({
        waypoints: [
            L.latLng(coordonates.from.lat, coordonates.from.lng),
            L.latLng(coordonates.to.lat, coordonates.to.lng)
        ],
        router: L.Routing.mapbox('pk.eyJ1IjoicGllcnNvbjM4IiwiYSI6ImNscDFndTkxZzBncnQyaXF1OXVkbjh6dW8ifQ.Qgl-uCRksKb7DAk0eK5ewg')
    }).addTo(map);
});