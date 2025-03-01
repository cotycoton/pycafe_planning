<?php
function getMoisFrancais($mois) {
    $moisFrancais = [
        1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril",
        5 => "Mai", 6 => "Juin", 7 => "Juillet", 8 => "Août",
        9 => "Septembre", 10 => "Octobre", 11 => "Novembre", 12 => "Décembre"
    ];

    return $moisFrancais[$mois] ?? "Mois invalide";
}

function week2024($semaine, $annee) {
    // Trouver le premier jour de la semaine donnée pour l'année d'origine
    $date = new DateTime();
    $date->setISODate($annee, $semaine); // Définit la semaine ISO

    // Calculer le numéro de semaine correspondant en 2024
    $date2024 = new DateTime();
    $date2024->setISODate(2024, 1); // Premier jour de 2024
    $diff = $date->diff($date2024)->days / 7; // Nombre de semaines entre les deux dates
    // Ajustement pour éviter les erreurs dues aux années bissextiles ou décalages ISO
    $numSemaine2024 = ceil(1 + $diff);

    // Vérifier si le nombre dépasse le nombre total de semaines en 2024 (53 semaines si applicable)
    //$totalSemaines2024 = (new DateTime("2024-12-28"))->format("W");
    //if ($numSemaine2024 > $totalSemaines2024) {
    //    $numSemaine2024 = $totalSemaines2024;
    //}

    return $numSemaine2024;
}



function generateCalendar($month, $year, $highlightWeek = null) {
    // Obtenir le premier jour du mois et le nombre de jours dans le mois
    $firstDayOfMonth = strtotime("$year-$month-01");
    $daysInMonth = date("t", $firstDayOfMonth);
    $firstWeekday = date("N", $firstDayOfMonth); // 1 (lundi) à 7 (dimanche)

    // Calculer les jours du mois précédent à afficher
    $prevMonthDays = $firstWeekday - 1;
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth == 0) {
        $prevMonth = 12;
        $prevYear--;
    }
    $nextYear = $year;
    $nextMonth = $month + 1;
    if ($nextMonth == 13)
    {
	   $nextMonth = 1;
	   $nextYear = $year+1;
    }
    $daysInPrevMonth = date("t", strtotime("$prevYear-$prevMonth-01"));

    // Construire le calendrier
    $moisF = getMoisFrancais((int)$month);
    echo "<div>";
    echo "<div align=\"center\">$moisF $year</div>";
    echo "<table border='1' style='border-collapse: collapse; text-align: center;' class=\"calendrier\">";
    echo "<tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr>";

    $currentWeekNumber = null;

    // Obtenir la date d'aujourd'hui
    $today = date("Y-m-d");
    // Afficher les jours du mois précédent
    for ($i = $prevMonthDays; $i > 0; $i--) {
        $dayPadded = sprintf("%02d", $daysInPrevMonth - $i +1);
        $monthPadded = sprintf("%02d", $prevMonth);
        //$dateString = "$prevYear-$prevMonth-" . ($daysInPrevMonth - $i + 1);
        $dateString = "$prevYear-$monthPadded-$dayPadded";
        $weekNumber = date("W", strtotime($dateString));
	$year_week = date("o", strtotime($dateString));
        if ($currentWeekNumber !== $weekNumber) {
            //echo "<tr><td colspan='7' style='text-align:center;'><a href='planning.php?week=$weekNumber'>Semaine $weekNumber</a></td></tr>";
    	    //echo "<tr onclick=\"window.location='planning.php?week=" . week2024($weekNumber,$year_week) . "'\" style=\"cursor:pointer;\">";
    	    echo "</tr><tr onclick=\"window.location='planning.php?week=" . $weekNumber . "&year=". $year_week . "'\" style=\"cursor:pointer;\">";
            $currentWeekNumber = $weekNumber;
        }
        $highlightStyle = ($highlightWeek == $weekNumber) ? "background-color: yellow;" : "";
	$todayStyle = ($dateString == $today) ? "background-color: green; color: white;" : "";
        echo "<td style='color: grey; $highlightStyle $todayStyle'>" . ($daysInPrevMonth - $i + 1) . "</td>";
    }

    // Afficher les jours du mois en cours
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dayPadded = sprintf("%02d", $day);
        $monthPadded = sprintf("%02d", $month);
        $dateString = "$year-$monthPadded-$dayPadded";
        $weekNumber = date("W", strtotime($dateString));
        $year_week = date("o", strtotime($dateString));
        if ($currentWeekNumber !== $weekNumber) {
            //echo "<tr><td colspan='7' style='text-align:center;'><a href='planning.php?week=$weekNumber'>Semaine $weekNumber</a></td></tr>";
    	    //echo "</tr><tr>";
    	    //echo "</tr><tr onclick=\"window.location='planning.php?week=" . week2024($weekNumber,$year_week) . "'\" style=\"cursor:pointer;\">";
    	    echo "</tr><tr onclick=\"window.location='planning.php?week=" . $weekNumber . "&year=". $year_week . "'\" style=\"cursor:pointer;\">";
            $currentWeekNumber = $weekNumber;
        }
        $highlightStyle = ($highlightWeek == $weekNumber) ? "background-color: yellow;" : "";
	$todayStyle = ($dateString == $today) ? "background-color: green; color: white;" : "";
        echo "<td style='font-weight: bold; $highlightStyle $todayStyle'>$day</td>";

    }

    // Afficher les jours du mois suivant
    $remainingCells = (7 - (($daysInMonth + $prevMonthDays) % 7)) % 7;
    for ($i = 1; $i <= $remainingCells; $i++) {
        $dayPadded = sprintf("%02d", $i);
        $monthPadded = sprintf("%02d", $nextMonth);
	$dateString = "$nextYear-" . $monthPadded . "-$dayPadded";
        $weekNumber = date("W", strtotime($dateString));
        $year_week = date("o", strtotime($dateString));
        if ($currentWeekNumber !== $weekNumber) {
            //echo "</tr><tr><td colspan='7' style='text-align:center;'><a href='planning.php?week=$weekNumber'>Semaine $weekNumber</a></td></tr><tr>";
    	    //echo '</tr><tr>';
    	    //echo "</tr><tr onclick=\"window.location='planning.php?week=" . week2024($weekNumber,$year_week) . "'\" style=\"cursor:pointer;\">";
    	    echo "</tr><tr onclick=\"window.location='planning.php?week=" . $weekNumber . "&year=". $year_week . "'\" style=\"cursor:pointer;\">";
            $currentWeekNumber = $weekNumber;
        }
        $highlightStyle = ($highlightWeek == $weekNumber) ? "background-color: yellow;" : "";
	$todayStyle = ($dateString == $today) ? "background-color: green; color: white;" : "";
        echo "<td style='color: grey; $highlightStyle $todayStyle'>$i</td>";
    }

    echo "</tr></table>";
    echo "</div>";
}

// Définir le mois, l'année et la semaine à surligner
//$month = isset($_GET['month']) ? intval($_GET['month']) : date("m");
//$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
//$highlightWeek = isset($_GET['week']) ? intval($_GET['week']) : null;

//generateCalendar($month, $year, $highlightWeek);
?>

