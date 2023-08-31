function init() {
    document.getElementById("boutonVille").addEventListener("click", envoieFormulaireVille);
}

window.init = init;

//////////////////////////////////////////// AFFICHAGE MAP   //////////////////////////////////////////////////////
function affichageMapVille() {
    mapVille = L.map('mapVille').setView([48.85889, 2.320041], 12);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright"></a>'
    }).addTo(mapVille);
    mapVille.on('click', onMapClick);
}


//////////////////////////////////////////// FLY TO EN FONCTION DU NOM VILLE   //////////////////////////////////////////////////////
function localisationVille() {
    // en fonction du nom de la ville
    let motInterdit = ["rue", "avenue", "boulevard", "route", "lieux", "lieux-dit", "lieu-dit"];
    // empêcher l'utilisateur de saisir des rue etc..
    var adresse = document.getElementById("adresseVille").value;
    let adresseFinal = adresse.replaceAll(" ", "+");

    const separatorRegExp = /[ -]/;
    let tabMot = adresse.toLowerCase().split(separatorRegExp);
    let interdit = 0;
    for (let mot of tabMot) {
        if (motInterdit.includes(mot)) {
            interdit = interdit + 1;
            document.getElementById("erreurMot").innerText = "Saisissez un nom de ville correct";
        }
    }
    if (interdit === 0) {
        document.getElementById("erreurMot").style.display = 'none';
        fetch(`https://nominatim.openstreetmap.org/search?q=${adresseFinal}&format=geojson`)
            .then(res => res.json())
            .then(
                json => {

                    let coordonnees = json['features'][0]['geometry']['coordinates'];

                    let longitude = coordonnees[0];
                    let latitude = coordonnees[1];
                    mapVille.flyTo([latitude, longitude], 16)
                    let longitudeText = document.getElementById('ville_longitude');
                    longitudeText.value = longitude;
                    let latitudeText = document.getElementById('ville_latitude')
                    latitudeText.value = latitude;
                    document.getElementById("ville_nom").value = adresse;
                    apiCodePostal(longitude, latitude);
                })
    }
}

window.localisationVille = localisationVille;

//////////////////////////////////////////// API CODE POSTAL   //////////////////////////////////////////////////////
function apiCodePostal(longitude, latitude) {

    fetch(`https://nominatim.openstreetmap.org/reverse?format=xml&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=`)
        .then(res => res.text())
        .then(xmlData => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlData, "text/xml");
            const postcodeElement = xmlDoc.querySelector("postcode");
            const codePostal = postcodeElement.textContent;

            document.getElementById("ville_codePostal").value = codePostal;
        });
}


function getLieuById(data, id) {
    return data.find(item => item.id === id);
}

//////////////////////////////////////////// VERIF EXISTANCE DU NOM D UNE VILLE   //////////////////////////////////////////////////////
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

////////////////////////////////////////////  FORMULAIRE   //////////////////////////////////////////////////////
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
                },
                body: JSON.stringify(data)
            }).then(reponse => reponse.json())
                .then(jsonData => {
                    let select = document.getElementById("villeListe");
                    let selectVilleLieux = document.getElementById("lieu_ville");
                    selectVilleLieux.innerText = "";
                    select.innerText = "";
                    const selectedId = document.getElementById("villeListe").value;
                    const selectedItem = getLieuById(jsonData, selectedId);
                    for (const lieu of selectedItem) {
                        let nouvelElement = document.createElement("option");
                        nouvelElement.setAttribute("value", lieu['id']);
                        nouvelElement.innerText = lieu['nom'];
                        select.appendChild(nouvelElement);
                        selectVilleLieux.appendChild(nouvelElement);
                    }
                });

            hideModalVille();
        }
    })
}

function resetFormulaireVille() {
    console.log("reset");
    document.getElementById("adresseVille").value = "";
    document.getElementById("ville_nom").value = "";
    document.getElementById("ville_codePostal").value = "";
    document.getElementById("ville_latitude").value = "";
    document.getElementById("ville_longitude").value = "";
}

//////////////////////////////////////////// SHOW/HIDE MODAL   //////////////////////////////////////////////////////
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