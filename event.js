// Charger la modale depuis modal.html au chargement de la page
//document.addEventListener("DOMContentLoaded", function () {
//    fetch("event.php")
//        .then(response => response.text())
//        .then(html => {
//            document.getElementById("modalContainer").innerHTML = html;
//        })
//        .catch(error => console.error("Erreur lors du chargement de la modale :", error));
//});
//
// Fonction pour ouvrir la modale
function openModal() {
    let modal = new bootstrap.Modal(document.getElementById('eventModal'));
    modal.show();
}

// Fonction pour récupérer les valeurs du formulaire et afficher en console
const myEvent = 
{
	createEvent:function() {
		let eventName = document.getElementById("eventName").value;
		let eventColor = document.getElementById("eventColor").value;
		let startTime = document.getElementById("startTime").value;
		let endTime = document.getElementById("endTime").value;
		let eventDetails = document.getElementById("eventDetails").value;

		if (!eventName || !startTime || !endTime) {
			alert("Veuillez remplir tous les champs obligatoires !");
			return;
		}

		console.log("Événement créé :", {
			nom: eventName,
			couleur: eventColor,
			début: startTime,
			fin: endTime,
			détails: eventDetails
		});

		let modal = bootstrap.Modal.getInstance(document.getElementById('eventModal'));
		modal.hide();
	}
};

document.addEventListener("DOMContentLoaded", function () {
    // Charger la modale dynamiquement
    fetch("event.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("modalContainer").innerHTML = html;

            // Ajouter l'événement après le chargement de la modale
            setupColorDropdown();
        })
        .catch(error => console.error("Erreur lors du chargement de la modale :", error));
});

function setupColorDropdown() {
    let colorDropdown = document.getElementById("eventColor");

    // Appliquer la couleur au chargement
    updateSelectColor();
    console.log("ecoute");
    // Écouter les changements pour mettre à jour la couleur
    colorDropdown.addEventListener("change", updateSelectColor);
}

function updateSelectColor() {
    let colorDropdown = document.getElementById("eventColor");
    let colorContainer = document.getElementById("indicator");
    let selectedOption = colorDropdown.options[colorDropdown.selectedIndex];

    // Récupérer la couleur depuis l'attribut data-color
    let selectedColor = selectedOption.getAttribute("data-color");

    // Appliquer la couleur au fond du select
    colorDropdown.style.backgroundColor = selectedColor;
    colorContainer.style.backgroundColor = selectedColor;
}

