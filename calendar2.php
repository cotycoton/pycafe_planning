<?php
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
    $daysInPrevMonth = date("t", strtotime("$prevYear-$prevMonth-01"));
    
    // Construire le calendrier
    echo "<table border='1' style='border-collapse: collapse; text-align: center;'>";
    echo "<tr><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th></tr>";
    echo "<tr>";
    
    // Variables de suivi de la semaine
    $currentWeek = date("W", strtotime("$year-$month-01"));
    $weekCounter = $currentWeek;
    
    // Afficher les jours du mois précédent
    for ($i = $prevMonthDays; $i > 0; $i--) {
        echo "<td style='color: grey;'>" . ($daysInPrevMonth - $i + 1) . "</td>";
    }
    
    // Afficher les jours du mois en cours
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateString = "$year-$month-$day";
        $weekNumber = date("W", strtotime($dateString));
        
        $highlightStyle = ($highlightWeek == $weekNumber) ? "background-color: yellow;" : "";
        echo "<td style='font-weight: bold; $highlightStyle'>$day</td>";
        
        if (($day + $prevMonthDays) % 7 == 0) {
            echo "</tr><tr>";
        }
    }
    
    // Afficher les jours du mois suivant
    $remainingCells = (7 - (($daysInMonth + $prevMonthDays) % 7)) % 7;
    for ($i = 1; $i <= $remainingCells; $i++) {
        echo "<td style='color: grey;'>$i</td>";
    }
    
    echo "</tr></table>";
}

// Définir le mois, l'année et la semaine à surligner
$month = isset($_GET['month']) ? intval($_GET['month']) : date("m");
$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$highlightWeek = isset($_GET['week']) ? intval($_GET['week']) : null;

generateCalendar($month, $year, $highlightWeek);
?>


