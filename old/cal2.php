<?php

function generateCalendarFromWeek($year, $weekNumber) {
    // Crée une date basée sur l'année et le numéro de semaine donné (lundi par défaut)
    $date = new DateTime();
    $date->setISODate($year, $weekNumber);

    // Récupère le mois et l'année du lundi de cette semaine
    $month = $date->format('m');
    $year = $date->format('Y');

    // Se positionne au premier jour du mois
    $firstDayOfMonth = new DateTime("$year-$month-01");
    $lastDayOfMonth = new DateTime("$year-$month-" . $firstDayOfMonth->format('t')); // dernier jour

    // Trouve le jour de la semaine (1 = lundi, 7 = dimanche)
    $startDay = (int)$firstDayOfMonth->format('N'); // Jour de la semaine (lundi = 1)
    $endDay = (int)$lastDayOfMonth->format('j');   // Dernier jour du mois

    // Style CSS pour surbrillance
    echo "<style>
            table { border-collapse: collapse; width: 100%; }
            th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
            th { background-color: #f4f4f4; }
            .highlight { background-color: #ffeb3b; } /* Jaune */
          </style>";

    // Génère le tableau du calendrier
    echo "<table>";
    echo "<tr><th colspan='7'>" . $firstDayOfMonth->format('F Y') . "</th></tr>";
    echo "<tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr>";

    // Remplissage des jours
    $day = 1 - ($startDay - 1); // Commence avant le 1er jour du mois si nécessaire
    for ($week = 1; $week <= 6 && $day <= $endDay; $week++) {
        $currentWeek = (new DateTime("$year-$month-$day"))->format('W'); // Numéro de la semaine en cours

        // Vérifie si la semaine correspond au $weekNumber à surligner
        $highlightClass = ($currentWeek == $weekNumber) ? "highlight" : "";

        echo "<tr class='$highlightClass'>";
        for ($d = 1; $d <= 7; $d++) {
            if ($day < 1 || $day > $endDay) {
                echo "<td></td>"; // Case vide
            } else {
                echo "<td>$day</td>";
            }
            $day++;
        }
        echo "</tr>";
    }

    echo "</table>";
}

// Exemple d'utilisation
$year = 2024;       // Année
$weekNumber = 50;    // Numéro de la semaine à surligner
generateCalendarFromWeek($year, $weekNumber);

?>


