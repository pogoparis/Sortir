
let marker;
function init() {
    console.log('je suis dans ville.js');
    document.getElementById("boutonVille").addEventListener("click", function(event){
        event.preventDefault()
    });
    document.getElementById("boutonVille").addEventListener("click", envoieFormulaireVille);
}
window.init = init;
function affichageMapVille(){
    console.log('je passe dans affiachage ville');
    mapVille = L.map('mapVille').setView([48.85889, 2.320041], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright"></a>'
    }).addTo(mapVille);
    mapVille.on('click', onMapClick);
}
// let popup = L.popup();
//
// function onMapVilleClick(e) {
//     let str = "Ville sélectionné"
//     let latitude = e.latlng.lat;
//     let longitude = e.latlng.lng;
//     let balise  = document.createElement("a");
//     balise.setAttribute("id", 'boutonCreaVilleMap');
//     let createAText = document.createTextNode(str);
//     balise.appendChild(createAText);
//     popup
//
//         .setLatLng(e.latlng)
//         .setContent(balise)
//         .openOn(mapVille)
//
//     let longitudeText = document.getElementById('ville_longitude');
//     longitudeText.value = longitude;
//     let latitudeText =document.getElementById('ville_latitude')
//     latitudeText.value = latitude;
// }
function localisationVille() {
    var adresse = document.getElementById("adresseVille").value;
    let longitude;
    let latitude;
    console.log(adresse);
    let adresseFinal = adresse.replaceAll(" ", "+");
    fetch(`https://nominatim.openstreetmap.org/search?q=${adresseFinal}&format=geojson`)
        .then(res => res.json())
        .then(
            json => {

                let coordonnees = json['features'][0]['geometry']['coordinates'];
                console.log('dans le json');
                console.log(coordonnees[0]);
                 longitude =  coordonnees[0];
                 latitude =  coordonnees[1];
                mapVille.flyTo([latitude, longitude], 16)
                let longitudeText = document.getElementById('ville_longitude');
                longitudeText.value = longitude;
                let latitudeText =document.getElementById('ville_latitude')
                latitudeText.value = latitude;
            } )

}
window.localisationVille = localisationVille;
function showModalVille() {
    var modalVille = document.getElementById('modalVille');
    modalVille.style.display = 'flex';
    affichageMapVille();
}
window.showModalVille = showModalVille;
function hideModalVille() {
    var modalVille = document.getElementById('modalVille');
    modalVille.style.display = 'none';
}
window.hideModalVille = hideModalVille;
function envoieFormulaireVille(event){

    event.preventDefault();

    const nom = document.getElementById("ville_nom").value;
    const codePostal = document.getElementById("ville_codePostal").value;
    const latitude = document.getElementById("ville_latitude").value;
    const longitude = document.getElementById("ville_longitude").value;


    let data = {
        nom: nom,
        codePostal: codePostal,
        latitude: latitude,
        longitude: longitude
    }
    console.log('je suis dans l envoie du formulaire');
    console.log(data);
    fetch('http://127.0.0.1:8000/creationVilleVide', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },body: JSON.stringify(data)
    }).then(reponse => reponse.json())

    hideModalVille();
}