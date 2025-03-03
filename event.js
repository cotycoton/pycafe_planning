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
     
		
    //const th = document.querySelectorAll('thead th');
    const th = document.querySelectorAll('thead th.col_jour');
    const select = document.querySelector('#eventDays');
    console.log(th);
    console.log(select);
    c=0;
    th.forEach
		(
			col => 
			{
				console.log(col.textContent);
				const opt1 = document.createElement("option");
				opt1.value=col.getAttribute("data-param");
				opt1.text=col.textContent;
				select.add(opt1,null);
				c++;
			}
		);


    modal.show();
}

// Fonction pour récupérer les valeurs du formulaire et afficher en console
const myEvent = 
{
	createEvent:function() {
		let eventName = document.getElementById("eventName").value;
		let eventColor = document.getElementById("eventColor").value;
		let eventDay = document.getElementById("eventDays").value;
		let startTime = document.getElementById("startTime").value;
		let endTime = document.getElementById("endTime").value;
		let eventDetails = document.getElementById("eventDetails").value;

		if (!eventName || !startTime || !endTime) {
			alert("Veuillez remplir tous les champs obligatoires !");
			return;
		}

		console.log("Événement créé :", {
			nom: eventName,
			jour: eventDay,
			couleur: eventColor,
			début: startTime,
			fin: endTime,
			détails: eventDetails
		});
    
		const events = document.querySelector('#Evenements');
		const tdElements = events.querySelectorAll('td.col_jour');
		tdElements.forEach
		(
			td => 
			{
				const attr = td.getAttribute('data-param');
				if ( attr == eventDay)
				{
					console.log(td);
					console.log("comp",attr, eventDay);
					const eventList = td.querySelector('ul');
					eventItem = document.createElement('li');
					divItem = document.createElement('div');
					divItem.innerHTML = eventName + " <br> " + startTime + " - " + endTime + "<div class=\"square " + eventColor + "\"></div>";
					eventItem.appendChild(divItem);
					eventList.appendChild(eventItem);
				}
			}
		);

		console.log(events);

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

