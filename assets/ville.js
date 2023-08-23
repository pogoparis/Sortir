window.onload = init;
function init() {
    fetch('http://127.0.0.1:8000/api')
        .then(res => res.json())
        .then(
            json => {
                let ville = document.getElementById("villeTest");
                let nomVille = json['nom'];
                ville.innerText = 'ville : ' + nomVille;
            }
        )
}
