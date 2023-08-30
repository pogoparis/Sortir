window.onload = init2

let marker;
function init2() {
    console.log('je passe dans map.js');
    init();
    affichageVille();
    afficherLocalisation();
    affichageLieuxVide();

    document.getElementById("boutonLieu").addEventListener("click", function(event){
        event.preventDefault()
    });
    document.getElementById("boutonLieu").addEventListener("click", envoieFormulaire);
}

function affichageMapLieu(){
    map = L.map('map').setView([48.85889, 2.320041], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright"></a>'
    }).addTo(map);
    map.on('click', onMapClick);
}
function resetFormulaireLieux(){
    console.log("resetLieux");
    document.getElementById("adresse").value="";
    document.getElementById("lieu_nom").value="";
    document.getElementById("lieu_rue").value="";
    document.getElementById("lieu_latitude").value="";
    document.getElementById("lieu_longitude").value="";
}
function affichageLieuxVide(){
    let select = document.getElementById("selectLieux");
    select.innerText = '';

        let option0 = document.createElement("option");
        option0.setAttribute("value", '0')
        option0.innerText = 'Choisir un lieu';
        select.appendChild(option0);

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
    fetch('http://127.0.0.1:8000/api')
        .then(res => res.json())
        .then(
            json => {
                let select = document.getElementById("selectLocalisation");
                select.innerText="";
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
window.afficherLocalisation = afficherLocalisation;
function allLieux() {

    $id = document.getElementById("villeListe").value;
    let select = document.getElementById("selectLieux");
        select.innerText = '';

    if($id !== 0){
        let option0 = document.createElement("option");
        option0.setAttribute("value", '0')
        option0.innerText = 'Choisir un lieu';
        select.appendChild(option0);

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
    console.log('l id est =' + $id);
    fetch('http://127.0.0.1:8000/apiLocalisation')
        .then(res => res.json())
        .then(
            json => {
                console.log('json0');
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
window.coordonnee = coordonnee;

    let popup = L.popup();

    function onMapClick(e) {
        let str = "Lieu sélectionné"
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

        let longitudeText = document.getElementById('lieu_longitude');
        longitudeText.value = longitude;
        let latitudeText =document.getElementById('lieu_latitude')
        latitudeText.value = latitude;
    }
    function localisationLieu() {
        var adresse = document.getElementById("adresse").value;
        const mots = adresse.split(" ");
        const premierMot = mots[0];
        let adresseFinal = adresse.replaceAll(" ", "+");
        fetch(`https://nominatim.openstreetmap.org/search?q=${adresseFinal}&format=geojson`)
            .then(res => res.json())
            .then(
                json => {

                    let coordonnees = json['features'][0]['geometry']['coordinates'];
                    let rue = json['features'][0]['properties']['name'];
                    console.log(rue);
                            let longitude =  coordonnees[0];
                            let latitude =  coordonnees[1];
                            map.flyTo([latitude, longitude], 16);
                    document.getElementById("lieu_rue").value = premierMot +" " + rue;
                    document.getElementById("lieu_latitude").value = latitude;
                    document.getElementById("lieu_longitude").value = longitude;
                    apiNomVille(longitude,latitude);
                } )
    }
    window.localisationLieu = localisationLieu;

function envoieFormulaire(event){

    event.preventDefault();

    const nom = document.getElementById("lieu_nom").value;
    const rue = document.getElementById("lieu_rue").value;
    const latitude = document.getElementById("lieu_latitude").value;
    const longitude = document.getElementById("lieu_longitude").value;
    const ville = document.getElementById("lieu_ville").options[document.getElementById("lieu_ville").selectedIndex].value;
    var regex = /^[a-zA-Z0-9]+$/u;

    if (!regex.test(nom)) {
        document.getElementById("erreurNom").textContent = "Le nom doit être composé de lettres et/ou de chiffres.";
    } else {
        let data = {
            nom: nom,
            rue: rue,
            latitude: latitude,
            longitude: longitude,
            ville: ville
        }
        console.log(data);
        fetch('http://127.0.0.1:8000/creationLieuVide', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }, body: JSON.stringify(data)
        }).then(reponse => reponse.json())

        hideModalLieu();
    }
}
function showModalLieu() {
    var modal = document.getElementById('modalLieu');
    modal.style.display = 'flex';
    resetFormulaireLieux();
    affichageMapLieu();
    afficherLocalisation();
}
window.showModalLieu = showModalLieu;
function hideModalLieu() {
    var modal = document.getElementById('modalLieu');
    modal.style.display = 'none';
}
window.hideModalLieu = hideModalLieu;

function apiNomVille(longitude, latitude){
    console.log('je suis dans apiVille');
    console.log(longitude);
    console.log(latitude);
    fetch(`https://nominatim.openstreetmap.org/reverse?format=xml&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=`)
        .then(res => res.text())
        .then(xmlData => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlData, "text/xml");

            const municipalityElement = xmlDoc.querySelector("municipality");

            const ville = municipalityElement.textContent;
            console.log('la ville ' + ville);

            const lieuVilleSelect = document.getElementById("lieu_ville");

            for (let i = 0; i < lieuVilleSelect.options.length; i++) {
                const option = lieuVilleSelect.options[i];
                if (option.textContent.trim() === ville) {
                    console.log('Option value =' + option.value);
                    option.selected = true;
                }
            }
        });
}