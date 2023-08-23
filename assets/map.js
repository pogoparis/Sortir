window.onload = init;

let marker;
function init() {
    map = L.map('map').setView([51.505, -0.09], 13);
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}\'', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    affichageVille();
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
                       console.log('idville' + typeof $idVille);
                        console.log('idvilleint' + typeof $idVilleInt);
                        if ($idVille !== $idVilleInt){
                            console.log('ça marche')
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
    let nouvelElement = document.createElement("option");
    // nouvelElement.setAttribute("value", '0');
    // nouvelElement.innerText = 'choisir un lieu';
    // select.appendChild(nouvelElement);
    if($id !== 0){
        fetch(`http://127.0.0.1:8000/apiLieux/${$id}`)
        .then(res => res.json())
        .then(
            json => {

                for (const js of json){

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