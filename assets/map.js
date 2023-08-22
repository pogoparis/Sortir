window.onload = init;

let marker;
function init() {
    map = L.map('map').setView([51.505, -0.09], 3);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}\'', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    setInterval(coordonnee, 1000);
}

function coordonnee() {
    fetch('http://api.open-notify.org/iss-now.json')
        .then(res => res.json())
        .then(
            json => {
                let iss = document.getElementById("map");
                        let latitude1 = document.getElementById("latitude");
                        let longitude1 = document.getElementById("longitude");
                        let longitude = json['iss_position']['longitude'];
                        let latitude = json['iss_position']['latitude'];
                        latitude1.innerText = 'latitude  : ' + latitude ;
                        longitude1.innerText = 'longitude : ' + longitude;
                        map.flyTo([latitude, longitude], 3);
            }
        )
}