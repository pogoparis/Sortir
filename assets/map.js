window.onload = init;

let marker;
function init() {
    affichageVille();
    afficherLocalisation();

    document.getElementById("boutonLieu").addEventListener("click", function(event){
        event.preventDefault()
    });
    document.getElementById("boutonLieu").addEventListener("click", envoieFormulaire);
}

function affichageMap(){
    map = L.map('map').setView([48.85889, 2.320041], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright"></a>'
    }).addTo(map);
    map.on('click', onMapClick);
}
function affichageVille() {
    fetch('http://127.0.0.1:8000/api')
        .then(res => res.json())
        .then(
            json => {
                let select = document.getElementById("villeListe");
                let select2 = document.getElementById("selectVille2");
                for (const js of json){

                    let nouvelElement = document.createElement("option");

                    nouvelElement.setAttribute("value", js['id'])
                    nouvelElement.innerText = js['nom'];
                    select.appendChild(nouvelElement);
                    // select2.appendChild(nouvelElement);
                    document.getElementById("villeListe").addEventListener("click", allLieux);
                }
            }
        )
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

function allLieux() {

    $id = document.getElementById("villeListe").value;
    let select = document.getElementById("selectLieux");
        select.innerText = '';

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

                map.flyTo([latitude, longitude], 16);
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
        let balise  = document.createElement("a");
        balise.setAttribute("id", 'boutonCreaMap');
        let createAText = document.createTextNode(str);
        balise.appendChild(createAText);
        popup

            .setLatLng(e.latlng)
            .setContent(balise)
            .openOn(map)

        document.getElementById('boutonCreaMap').addEventListener('click', function() {
            showModal(latitude, longitude);
        });
        let longitudeText = document.getElementById('lieu_longitude');
        longitudeText.value = longitude;
        let latitudeText =document.getElementById('lieu_latitude')
        latitudeText.value = latitude;
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
    }
    window.localisationLieu = localisationLieu;

function showModal() {
    var modal = document.getElementById('modal');
    modal.style.display = 'flex';
    affichageMap();
}
window.showModal = showModal;
function hideModal() {
    var modal = document.getElementById('modal');
    modal.style.display = 'none';
}
window.hideModal = hideModal;
function envoieFormulaire(event){

    event.preventDefault();

    const nom = document.getElementById("lieu_nom").value;
    const rue = document.getElementById("lieu_rue").value;
    const latitude = document.getElementById("lieu_latitude").value;
    const longitude = document.getElementById("lieu_longitude").value;
    const ville = document.getElementById("lieu_ville").options[document.getElementById("lieu_ville").selectedIndex].value;

    let data = {
        nom: nom,
        rue: rue,
        latitude: latitude,
        longitude: longitude,
        ville : ville
    }
    console.log(data);
    fetch('http://127.0.0.1:8000/creationLieuVide', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },body: JSON.stringify(data)
    }).then(reponse => reponse.json())

    hideModal();
}
