
<script>
async function ajouterReservation(date, plage, id, nom, prenom, cowork, commentaire) {
    const url = "ajouter_reservation.php"; // Adapter selon votre serveur
    const data = {
        date: date,
        plage: plage,
        id: id,
        nom: nom,
        prenom: prenom,
        cowork: cowork,
        commentaire: commentaire
    };

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
            alert("Réservation ajoutée avec succès !");
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

</script>
<script src="db_planning_add.js"></script> <!-- Import du JS -->



<script>

getReservations("2025-02-25", "08:00-10:00").then(data => {
    if (data) {
        console.log("Réservations :", data);
    } else {
        console.log("Aucune réservation trouvée ou erreur.");
    }
});

</script>
