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
     
	const h5 = document.querySelector('#eventModal h5');
	h5.textContent = 'Créer un evenement';
	
	const creer_b = document.querySelector('#eventModal #create_button');
	const delete_b = document.querySelector('#eventModal #delete_button');
	const cancel_b = document.querySelector('#eventModal #cancel_button');
	creer_b.textContent = 'Créer';
	creer_b.dataset.create = -1;
			
	creer_b.classList.add('visible-button');
	creer_b.classList.remove('hidden-button');
	cancel_b.classList.add('visible-button');
	cancel_b.classList.remove('hidden-button');
	delete_b.classList.remove('visible-button');
	delete_b.classList.add('hidden-button');

	const eventName = document.querySelector('#eventModal #eventName');
	eventName.value = '';

	const startTime = document.querySelector('#eventModal #startTime');
	startTime.value = '';

	const endTime = document.querySelector('#eventModal #endTime');
	endTime.value = '';

	const detail = document.querySelector('#eventModal #eventDetails');
	detail.value = '';
	
	const persons = document.querySelector('#eventModal #eventPersonnes');
	persons.value = '1';

	const color = document.querySelector('#eventModal #eventColor');
	color.value = 'red';

	updateSelectColor();

		
	//const th = document.querySelectorAll('thead th');
	const th = document.querySelectorAll('thead th.col_jour');
	const select = document.querySelector('#eventDays');
	const select_option = document.querySelectorAll('#eventModal #eventDays option');
	if (select_option.length == 0)
	{
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
	}


    modal.show();
}

// Fonction pour récupérer les valeurs du formulaire et afficher en console
const myEvent = 
{
	createEvent: async function() {
		let eventName = document.getElementById("eventName").value;
		let eventColor = document.getElementById("eventColor").value;
		let eventDay = document.getElementById("eventDays").value;
		let startTime = document.getElementById("startTime").value;
		let endTime = document.getElementById("endTime").value;
		let eventDetails = document.getElementById("eventDetails").value;
		let eventPersonnes = document.getElementById("eventPersonnes").value;

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
			détails: eventDetails,
			personnes: eventPersonnes
		});
    


		let eventData = {
			nom: eventName,
			date_event: eventDay,
			heure_debut: startTime,
			heure_fin: endTime,
			color: eventColor,
			details: eventDetails,
			personnes : eventPersonnes,
			ressources: ""
		};
	
		const creer_b = document.querySelector('#eventModal #create_button');
		if (creer_b.dataset.create!="-1")
		{
			eventData.id = creer_b.dataset.create;
		}

		console.log("EVENDATA",eventData);
		
		const events = document.querySelector('#Evenements');
		const tdElements = events.querySelectorAll('td.col_jour');


		
		const eventId = await AsyncSaveOrUpdateEvent(eventData);
		
		if (eventId)
		{
			if (creer_b.dataset.create!="-1")
			{
				// suppression ancien evenement (html seulement)
				document.getElementById("href_event_"+eventId).remove();
			}
			// creation evenement
			let lien = null;
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
						lien = document.createElement('a');
						lien.href="#";
						if (eventPersonnes > 0)
							divItem.innerHTML = eventName + " <br> " + startTime + " - " + endTime + "<div class=\"square " + eventColor + "\"><div class=\"person\">" + eventPersonnes + "</div></div>";
						else
							divItem.innerHTML = eventName + " <br> " + startTime + " - " + endTime + "<div class=\"square " + eventColor + "\"></div>";
						eventItem.appendChild(divItem);
						lien.appendChild(eventItem)
						eventList.appendChild(lien);
					}
				}
			);
			console.log("AsyncSaveOrUpdateEvent, id = ",eventId);
			lien.setAttribute("onclick", "editEvent(this," + eventId + ")");
			lien.id="href_event_" + eventId;
		};



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



async function AsyncSaveOrUpdateEvent(eventData) {

	try
	{
		const response = await fetch('save_event.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify(eventData)
		});

		const data = await response.json();
		console.log(data);
		return data.id; // Retourne l'ID pour un usage futur
	} catch(error) 	{ 
		console.error('Erreur:', error);
		return null;
	}
}




async function deleteEvent(eventId) {
    try	{
	const response = await fetch('delete_event.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: eventId })
	});
	    const data = await response.json();
	    if (data.success)
	    {
		    document.getElementById("href_event_"+eventId).remove();
		    console.log('Événement supprimé avec succès');
	    }
	    else {
		    console.error('Erreur lors de la suppression:', data.error);
	    }

    } catch(error)
	{
		console.error('Erreur:', error);
	}
}



function getEventById(eventId) {
    return fetch('get_event.php?id=' + eventId)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP: " + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                return data.event; // Retourne l'événement sous forme d'objet
            } else {
                throw new Error("Erreur: " + data.error);
            }
        })
        .catch(error => {
            console.error("Erreur lors de la récupération de l'événement:", error);
        });
}

async function asyncGetEventById(eventId) {

	
    const url = `get_event.php?id=${encodeURIComponent(eventId)}`;
    console.log('url',url);
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const data = await response.json();
        console.log("data",data); // Affiche les résultats dans la console

        return data; // Retourne les données récupérées
    } catch (error) {
        console.error("Erreur lors de la récupération des réservations :", error);
        return null;
    }

}
async function getEventsByDate(eventDate) {
	
	
    const url = `get_event.php?date=${encodeURIComponent(eventDate)}`;
    console.log('url',url);
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const data = await response.json();
        console.log("data",data); // Affiche les résultats dans la console

        return data; // Retourne les données récupérées
    } catch (error) {
        console.error("Erreur lors de la récupération des réservations :", error);
        return null;
    }

}



function editEvent(node,id)
{
	
	// Exemple d'utilisation
	getEventById(id).then(event => {
		if (event) {

			console.log("Nom de l'événement:", event.nom);
			//let modal = document.getElementById('eventModal');
			let modal = new bootstrap.Modal(document.getElementById('eventModal'));

			const h5 = document.querySelector('#eventModal h5');
			h5.textContent = 'Modification de l\'evenement';

			
			const creer_b = document.querySelector('#eventModal #create_button');
			const delete_b = document.querySelector('#eventModal #delete_button');
			const cancel_b = document.querySelector('#eventModal #cancel_button');
			creer_b.textContent = 'Sauvegarder';
			creer_b.dataset.create = id;
			
		        delete_b.classList.remove('hidden-button');
		        delete_b.classList.add('visible-button');
	    		delete_b.setAttribute('onclick', "deleteEvent(" + event.id + ")");


			const eventName = document.querySelector('#eventModal #eventName');
			eventName.value = event.nom;
			
			const eventDays = document.querySelector('#eventModal #eventDays');
			const eventDays_option = document.querySelectorAll('#eventModal #eventDays option');
			if (eventDays_option.length == 0)
			{

				const th = document.querySelectorAll('thead th.col_jour');
				th.forEach
				(
					col => 
					{
						console.log(col.textContent);
						const opt1 = document.createElement("option");
						opt1.value=col.getAttribute("data-param");
						opt1.text=col.textContent;
						eventDays.add(opt1,null);
					}
				);
			}
			eventDays.value=event.date_event;
			
			const startTime = document.querySelector('#eventModal #startTime');
			startTime.value = event.heure_debut;
			
			const endTime = document.querySelector('#eventModal #endTime');
			endTime.value = event.heure_fin;

			const detail = document.querySelector('#eventModal #eventDetails');
			detail.value = event.details;
			
			const persons = document.querySelector('#eventModal #eventPersonnes');
			persons.value = event.personnes;
			
			const color = document.querySelector('#eventModal #eventColor');
			color.value = event.color;

			updateSelectColor();


			const mydiv = document.getElementById('myDiv');
			const admin = mydiv.getAttribute('data-admin');
			const connected = mydiv.getAttribute('data-connected');
			isAdmin = (admin == "1") && (connected == "1");

			if (isAdmin ==false)
			{
				eventName.disabled=true;
				eventDays.disabled=true;
				color.disabled=true;
				detail.disabled=true;
				startTime.disabled=true;
				endTime.disabled=true;
				persons.disabled = true;

				delete_b.classList.remove('visible-button');
				delete_b.classList.add('hidden-button');

				creer_b.classList.remove('visible-button');
				creer_b.classList.add('hidden-button');

				cancel_b.classList.remove('visible-button');
				cancel_b.classList.add('hidden-button');

				h5.textContent = 'Information évenement';
			}
			//console.log(event);
			modal.show();

		}
	});
    


}


//
//
//const { Pool } = require('pg');
//
//// Configuration de la connexion PostgreSQL
//const pool = new Pool({
//    user: 'root',
//    host: 'localhost',
//    database: 'EPICAFE_events',
//    password: '',
//    port: 5432, // Port par défaut de PostgreSQL
//});
//
//async function getEventsByDate(date) {
//    const sql = "SELECT * FROM events WHERE date_event = $1";
//    try {
//        const result = await pool.query(sql, [date]);
//        return result.rows; // Retourne les événements sous forme de tableau d'objets
//    } catch (error) {
//        console.error("Erreur lors de la récupération des événements :", error);
//        return [];
//    }
//}
//
////// Exemple d'utilisation
////async function main() {
////    const events = await getEventsByDate('2025-03-09');
////    console.log(events);
////}
//
