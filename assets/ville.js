
let marker;
function init() {
    console.log('je suis dans ville.js');
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
function resetFormulaireVille(){
    console.log("reset");
    document.getElementById("adresseVille").value="";
    document.getElementById("ville_nom").value="";
    document.getElementById("ville_codePostal").value="";
    document.getElementById("ville_latitude").value="";
    document.getElementById("ville_longitude").value="";
}
function localisationVille() {


    var adresse = document.getElementById("adresseVille").value;
    let adresseFinal = adresse.replaceAll(" ", "+");

    fetch(`https://nominatim.openstreetmap.org/search?q=${adresseFinal}&format=geojson`)
        .then(res => res.json())
        .then(
            json => {

                let coordonnees = json['features'][0]['geometry']['coordinates'];

                console.log('dans le json');
                let longitude =  coordonnees[0];
                let latitude =  coordonnees[1];
                console.log(latitude);
                console.log(longitude);
                mapVille.flyTo([latitude, longitude], 16)
                let longitudeText = document.getElementById('ville_longitude');
                longitudeText.value = longitude;
                let latitudeText =document.getElementById('ville_latitude')
                latitudeText.value = latitude;
                document.getElementById("ville_nom").value = adresse;
                apiCodePostal(longitude,latitude);
            } )

}
window.localisationVille = localisationVille;
function apiCodePostal(longitude, latitude){

    fetch(`https://nominatim.openstreetmap.org/reverse?format=xml&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=`)
        .then(res => res.text())
        .then(xmlData => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlData, "text/xml");

            const postcodeElement = xmlDoc.querySelector("postcode");

            const codePostal = postcodeElement.textContent;
            console.log('le code postal' + codePostal)
            document.getElementById("ville_codePostal").value = codePostal;
        });
}

function showModalVille() {
    var modalVille = document.getElementById('modalVille');
    modalVille.style.display = 'flex';
    resetFormulaireVille();
    affichageMapVille();
}
window.showModalVille = showModalVille;
function hideModalVille() {
    var modalVille = document.getElementById('modalVille');
    modalVille.style.display = 'none';
    afficherLocalisation();
}
window.hideModalVille = hideModalVille;
function verifVille(nom) {

    return fetch('http://127.0.0.1:8000/api')
        .then(res => res.json())
        .then(json => {

            for (const js of json) {
                if (js['nom'] === nom) {
                    return 1;
                }
            }
            return 0;
        });
}
function envoieFormulaireVille(event) {

    event.preventDefault();

    const nom = document.getElementById("ville_nom").value;
    const codePostal = document.getElementById("ville_codePostal").value;
    const latitude = document.getElementById("ville_latitude").value;
    const longitude = document.getElementById("ville_longitude").value;

    const un = 1;

    verifVille(nom).then(resultat => {
        if (resultat === un) {
            document.getElementById("erreurVille").innerText = "La ville existe déjà, saisissez une autre ville";
        }
        if (resultat !== un) {
            let data = {
                nom: nom,
                codePostal: codePostal,
                latitude: latitude,
                longitude: longitude
            };

            fetch('http://127.0.0.1:8000/creationVilleVide', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }, body: JSON.stringify(data)
            }).then(reponse => reponse.json())
                .then(jsonData => {
                    let select = document.getElementById("villeListe");
                    select.innerText = "";
                    for (const js of jsonData) {

                        let nouvelElement = document.createElement("option");

                        nouvelElement.setAttribute("value", js['id'])
                        nouvelElement.innerText = js['nom'];
                        select.appendChild(nouvelElement);

                    }
                })
            hideModalVille();
        }
    })
}
