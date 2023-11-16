
const ACCESS_TOKEN = 'pk.eyJ1IjoicGllcnNvbjM4IiwiYSI6ImNscDFndTkxZzBncnQyaXF1OXVkbjh6dW8ifQ.Qgl-uCRksKb7DAk0eK5ewg';
const script = document.getElementById('search-js');
script.onload = () => {
    const collection = mapboxsearch.autofill({
        accessToken: ACCESS_TOKEN,
        options: {
            countries: 'fr',
            language: 'fr'
        }
    });

    collection.addEventListener('retrieve', (event) => {

        var type = event.target.dataset.type;

        const coordonates = event.detail.features[0].geometry.coordinates;

        let fromLat = document.getElementById('carpool_fromLat');
        let fromLng = document.getElementById('carpool_fromLong');
        let toLat = document.getElementById('carpool_toLat');
        let toLng = document.getElementById('carpool_toLong');

        if (type === 'from') {
            fromLat.value = coordonates[1];
            fromLng.value = coordonates[0];
        } else {
            toLat.value = coordonates[1];
            toLng.value = coordonates[0];
        }

    });

    console.log(collection);
};

