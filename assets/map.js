window.onload = init;

let marker;
function init() {
    map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}\'', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    let blackIcon = L.icon({
        iconUrl: 'pointeur.png',
        shadowUrl: 'leaf-shadow.png',

        iconSize: [30, 70], // size of the icon
        iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
        popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
    });
    marker = L.marker([51.505, -0.09], {icon: blackIcon}).addTo(map);
    affichageVille();
    afficherLocalisation();
}

function afficherLieu(){
     $id = document.getElementById("selectVille").value;

console.log($id);
    if($id !== 0){

        fetch(`http://127.0.0.1:8000/apiLieux/${$id}`)
            .then(res => res.json())
            .then(
             json => {
                    for (const js of json){
                        $idVille = js["ville"]["id"];
                        $idVilleInt = parseInt($idVille);

                        if ($idVille !== $idVilleInt){
                            document.getElementById("selectLieux").hidden = false;
                         $i = js["id"];
                         $lieu =  document.getElementById("selectLieux").options[$i];
                         $lieu.hidden = true;
                        }

                    }
             }
        )}
}
window.afficherLieu = afficherLieu;
function allLieux() {

    $id = document.getElementById("selectVille").value;
    let select = document.getElementById("selectLieux");
        select.innerText = '';

    // nouvelElement.setAttribute("value", '0');
    // nouvelElement.innerText = 'choisir un lieu';
    // select.appendChild(nouvelElement);
    if($id !== 0){
        fetch(`http://127.0.0.1:8000/apiLieux/${$id}`)
        .then(res => res.json())
        .then(
            json => {

                for (const js of json){
                    let nouvelElement = document.createElement("option");
                    nouvelElement.setAttribute("value", js['id'])
                    nouvelElement.innerText = js['nom'];
                    select.appendChild(nouvelElement);
                }
            }
        )}
}
function affichageVille() {
    fetch('http://127.0.0.1:8000/api')
        .then(res => res.json())
        .then(
            json => {
                let select = document.getElementById("selectVille");
                let select2 = document.getElementById("selectVille2");
                for (const js of json){

                    let nouvelElement = document.createElement("option");

                    nouvelElement.setAttribute("value", js['id'])
                    nouvelElement.innerText = js['nom'];
                    select.appendChild(nouvelElement);
                    select2.appendChild(nouvelElement);
                    document.getElementById("selectVille").addEventListener("click", allLieux);
                }
            }
        )
}
function afficherLocalisation() {
    fetch('http://127.0.0.1:8000/apiLocalisation')
        .then(res => res.json())
        .then(
            json => {
                let select = document.getElementById("selectLocalisation");

                for (const js of json){

                    let nouvelElement = document.createElement("option");

                    nouvelElement.setAttribute("value", js['id'])
                    nouvelElement.innerText = js['nom'];
                    select.appendChild(nouvelElement);
                    document.getElementById("selectLocalisation").addEventListener("click", coordonnee);
                }
            }
        )
}

function coordonnee() {

    $id = document.getElementById("selectLocalisation").value;
    console.log($id);
    fetch('http://127.0.0.1:8000/apiLocalisation')
        .then(res => res.json())
        .then(
            json => {
                console.log(json[0]);

                for (const js of json){
                    $idVilleInt = parseInt($id);
                    $idSelect = parseInt(js['id']);

                    if($idSelect === $idVilleInt){

                 let longitude = js['longitude'];
                 let latitude = js['latitude'];
                        let latitude1 = document.getElementById("latitude");
                        let longitude1 = document.getElementById("longitude");
                        latitude1.innerText = 'latitude  : ' + latitude ;
                        longitude1.innerText = 'longitude : ' + longitude;
                map.flyTo([latitude, longitude], 16);
                marker.setLatLng([latitude, longitude]);
            }
                }
            }
        )
}
    // window.coordonnee = coordonnee;
    let popup = L.popup();

    function onMapClick(e) {
        let str = "Cliquer pour créer le lieu"
        let latitude = e.latlng.lat;
        let longitude = e.latlng.lng;
        let lien = `http://127.0.0.1:8000/creationLocalisation/${latitude}/${longitude}`;
        let balise  = document.createElement("a");
        balise.setAttribute('href', lien);
        var createAText = document.createTextNode(str);
        balise.appendChild(createAText);
        popup

            .setLatLng(e.latlng)
            .setContent(balise)
            .openOn(map)


    }
    function localisationLieu() {
        var adresse = document.getElementById("adresse").value;
        for(const mot of adresse){

        }
        let adresseFinal = adresse.replaceAll(" ", "+");
        fetch(`https://nominatim.openstreetmap.org/search?q=${adresseFinal}&format=geojson`)
            .then(res => res.json())
            .then(
                json => {

                    let coordonnees = json['features'][0]['geometry']['coordinates'];
                            let longitude =  coordonnees[0];
                            let latitude =  coordonnees[1];
                            map.flyTo([latitude, longitude], 16);
                } )
        map.on('click', onMapClick);
    }
    window.localisationLieu = localisationLieu;

