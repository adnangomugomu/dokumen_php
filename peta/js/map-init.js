$(document).ready(function() {
    $('#example').DataTable();

    // Render Peta Spasial
    var map = L.map('map-id', {
        attributionControl: false,
        zoomControl: false
    }).setView([-7.57110295267244, 110.82622065004949], 14);

    map.scrollWheelZoom.disable();

    $('#zoom-in, #zoom-in').on('click', function() {
        map.zoomIn();
    })
    $('#zoom-out, #zoom-out').on('click', function() {
        map.zoomOut();
    })
    $('#center, #center').on('click', function() {
        map.flyTo([-7.57110295267244, 110.82622065004949], 14);
    })

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        minZoom: 6,
        tileSize: 512,
        zoomOffset: -1,
        scrollWheelZoom: false,
        id: 'mapbox/streets-v11',
        accessToken: 'pk.eyJ1IjoibmlyZWRvY3oiLCJhIjoiY2tsMjhpM3BkM3JpcDJvcW42cXo3NGNnMSJ9.rBOz7uajzNiVUcgbDfiZ0A'
    }).addTo(map);
});