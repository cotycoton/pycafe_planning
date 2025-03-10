async function ajouterReservation(date, plage, id, nom, prenom, cowork, commentaire, events) {
    const url = "ajouter_reservation.php"; // Adapter selon votre serveur
    const data = {
        date: date,
        plage: plage,
        id: id,
        nom: nom,
        prenom: prenom,
        cowork: cowork,
        commentaire: commentaire,
	events: events
    };

    console.log(data);
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        console.log(result);

        if (result.success) {
            //alert("Réservation ajoutée avec succès !");
        } else {
            alert("Erreur : " + result.error);
        }
    } catch (error) {
        console.error("Erreur réseau :", error);
        alert("Une erreur réseau est survenue.");
    }
}

// Exemple d'utilisation :
//ajouterReservation("2025-02-25", "08:00-10:00", "ABC123", "Dupont", "Jean", true, "Besoin dun bureau calme");
//ajouterReservation("2025-02-25", "08:00-10:00", "ABC1", "Dupont", "Jean", true, "Besoin dun bureau calme");

async function getReservations(date, plage) {
    const url = `get_reservation.php?date=${encodeURIComponent(date)}&plage=${encodeURIComponent(plage)}`;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Erreur HTTP : ${response.status}`);
        }

        const data = await response.json();
        console.log(data); // Affiche les résultats dans la console

        return data; // Retourne les données récupérées
    } catch (error) {
        console.error("Erreur lors de la récupération des réservations :", error);
        return null;
    }
}

function deleteReservation(date, plage, id) {
    fetch("delete_reservation.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            date: date,
            plage: plage,
            id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            //alert("Réservation supprimée avec succès !");
            location.reload(); // Recharge la page pour mettre à jour la liste
        } else {
            alert("Erreur : " + data.message);
        }
    })
    .catch(error => console.error("Erreur lors de la requête :", error));
}



// Exemple d'utilisation :
//getReservations("2025-02-25", "08:00-10:00").then(data => {
//    if (data) {
//        console.log("Réservations :", data);
//    } else {
//        console.log("Aucune réservation trouvée ou erreur.");
//    }
//});


function enregistrerEtat(date, plageHoraire, etat) {
    fetch('db_save_etat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ date: date, plage_horaire: plageHoraire, etat: etat })
    })
    .then(response => response.json())
    .then(data => console.log(data.message))
    .catch(error => console.error('Erreur:', error));
}


