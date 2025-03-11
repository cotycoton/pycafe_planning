//
//document.addEventListener("DOMContentLoaded", function () {
//    // Charger la modale dynamiquement
//    fetch("help.php")
//        .then(response => response.text())
//        .then(html => {
//            document.getElementById("modalHelp").innerHTML = html;
//
//        })
//        .catch(error => console.error("Erreur lors du chargement de la modale :", error));
//});
//


// Récupérer les éléments
const modalHelp = document.getElementById("modalHelp");
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtnHelp");
const images = document.querySelectorAll(".lazy-load");

// Fonction pour charger les images
function loadImages() {
    images.forEach(image => {
        // Vérifier si l'image n'est pas encore chargée
        if (image.hasAttribute('data-src')) {
            image.src = image.getAttribute('data-src');
            image.removeAttribute('data-src');
            image.onload = () => image.classList.add('loading'); // Optionnel, pour marquer l'image comme chargée
        }
    });
}

// Ouvrir la fenêtre modale
//openModalBtn.onclick = function() {
//    modalHelp.style.display = "block";
//    console.log("aide");
//    loadImages(); // Charger les images lorsque la modale est affichée
//}

// Fermer la fenêtre modale
closeModalBtn.onclick = function() {
    //modalHelp.style.display = "none";
		let modal = bootstrap.Modal.getInstance(document.getElementById('modal-help'));
	modal.hide();
}

// Fermer la fenêtre modale si l'utilisateur clique en dehors de celle-ci
window.onclick = function(event) {
    if (event.target === modalHelp) {
        modalHelp.style.display = "none";
    }
}


function openModalHelp() {
	let modal = new bootstrap.Modal(document.getElementById('modal-help'));
    	console.log("Aide");
	modal.show();
    loadImages(); // Charger les images lorsque la modale est affichée

}
     
