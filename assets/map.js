window.onload = init;

let marker;
function init() {
    map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}\'', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
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

                for (const js of json){

                    let nouvelElement = document.createElement("option");

                    nouvelElement.setAttribute("value", js['id'])
                    nouvelElement.innerText = js['nom'];
                    select.appendChild(nouvelElement);
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

                map.flyTo([latitude, longitude], 16);
            }
                }
            }
        )
    window.coordonnee = coordonnee;
}