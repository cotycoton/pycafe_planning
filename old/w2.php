

<?php
// Récupérer le numéro de semaine depuis le paramètre URL
$weekNumber = isset($_GET['week']) ? (int)$_GET['week'] : 1;

// Validation du numéro de semaine
if ($weekNumber < 1 || $weekNumber > 52) {
    die("Numéro de semaine invalide. Veuillez fournir un numéro entre 1 et 52.");
}

// Année cible (2024)
$year = 2024;

// Calculer la date du lundi de la semaine donnée
$timestamp = strtotime("$year-W$weekNumber-1");

// Obtenir les dates des jours de la semaine (lundi à dimanche)
$daysOfWeek = [];
for ($i = 0; $i < 7; $i++) {
    $daysOfWeek[] = date('d-m-Y', strtotime("+$i day", $timestamp));
}

// Plages horaires
$timeSlots = [
    "", // Ligne vide pour l'affichage
    "8h10-10h30",
    "10h30-12h30",
    "", // Ligne vide pour l'affichage
    "15h30-17h30",
    "17h30-19h30"
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de la Semaine <?php echo $weekNumber; ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Tableau de la Semaine <?php echo $weekNumber; ?> (Année <?php echo $year; ?>)</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Lundi<br><?php echo $daysOfWeek[0]; ?></th>
                <th>Mardi<br><?php echo $daysOfWeek[1]; ?></th>
                <th>Mercredi<br><?php echo $daysOfWeek[2]; ?></th>
                <th>Jeudi<br><?php echo $daysOfWeek[3]; ?></th>
                <th>Vendredi<br><?php echo $daysOfWeek[4]; ?></th>
                <th>Samedi<br><?php echo $daysOfWeek[5]; ?></th>
                <th>Dimanche<br><?php echo $daysOfWeek[6]; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($timeSlots as $index => $timeSlot) {
                echo "<tr>";
                echo "<td>" . $timeSlot . "</td>";
                for ($col = 0; $col < 7; $col++) {
                    if ($timeSlot !== "") {
                        echo "<td>Cell " . ($index + 1) . "-" . ($col + 1) . "</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>


