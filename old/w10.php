

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

// Couleurs pour certaines cellules
$highlightedCells = [
    ["day" => 0, "times" => ["15h30-17h30", "17h30-19h30"]], // Lundi
    ["day" => 2, "times" => $timeSlots], // Mercredi (toutes les plages horaires)
    ["day" => 4, "times" => ["15h30-17h30", "17h30-19h30"]], // Vendredi
    ["day" => 5, "times" => ["8h10-10h30", "10h30-12h30"]] // Samedi
];

// Précédent et suivant
$prevWeek = $weekNumber > 1 ? $weekNumber - 1 : 52;
$nextWeek = $weekNumber < 52 ? $weekNumber + 1 : 1;

// Date actuelle
$currentDate = date('d-m-Y');

// Liste des utilisateurs
$users = ["user1", "user2", "user3", "user4", "user5", "user6", "user7", "user8", "user9", "user10"];
$currentUser = "user1";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de la Semaine <?php echo $weekNumber; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
        .highlight {
            background-color: #ffeb3b;
            cursor: pointer;
        }
        .selected {
            background-color: #ffa726 !important;
        }
        .nav-arrows {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }
        .nav-arrows a {
            text-decoration: none;
            font-size: 24px;
            color: #000;
        }
        .today {
            background-color: #4caf50 !important;
            color: white;
        }
        .user-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }
        .user-list li {
            padding: 2px 0;
        }
        .action-button {
            margin-left: 10px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cells = document.querySelectorAll('.highlight');

            cells.forEach(cell => {
                cell.addEventListener('click', () => {
                    // Retirer la sélection des autres cellules
                    document.querySelectorAll('.selected').forEach(selectedCell => {
                        selectedCell.classList.remove('selected');
                    });

                    // Ajouter la classe selected à la cellule cliquée
                    cell.classList.add('selected');
                });
            });

            document.body.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-user')) {
                    const userList = event.target.closest('.user-list');
                    const addButton = document.createElement('button');
                    addButton.textContent = 'Ajouter';
                    addButton.className = 'action-button add-user';
                    addButton.addEventListener('click', () => {
                        const userItem = document.createElement('li');
                        userItem.textContent = 'user1';
                        const removeButton = document.createElement('button');
                        removeButton.innerHTML = '<i class="bi bi-trash"></i>';
                        removeButton.className = 'action-button remove-user';
                        removeButton.addEventListener('click', () => removeButton.closest('li').remove());
                        userItem.appendChild(removeButton);
                        userList.appendChild(userItem);
                        addButton.remove();
                    });
                    userList.appendChild(addButton);
                    event.target.closest('li').remove();
                }
            });
        });
    </script>
</head>
<body>
    <div class="nav-arrows">
        <a href="?week=<?php echo $prevWeek; ?>" title="Semaine précédente">&#8592;</a>
        <a href="?week=<?php echo $nextWeek; ?>" title="Semaine suivante">&#8594;</a>
    </div>

    <h1>Tableau de la Semaine <?php echo $weekNumber; ?> (Année <?php echo $year; ?>)</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <?php
                foreach ($daysOfWeek as $index => $day) {
                    $class = ($day === $currentDate) ? "today" : "";
                    echo "<th class='$class'>" . date('l', strtotime($day)) . "<br>" . $day . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($timeSlots as $index => $timeSlot) {
                echo "<tr>";
                echo "<td>" . $timeSlot . "</td>";
                for ($col = 0; $col < 7; $col++) {
                    $cellClass = "";
                    foreach ($highlightedCells as $highlight) {
                        if ($highlight["day"] == $col && in_array($timeSlot, $highlight["times"])) {
                            $cellClass = "highlight";
                            break;
                        }
                    }
                    if ($timeSlot === "") {
                        echo "<td></td>"; // Cellules vides pour les lignes sans plages horaires
                    } else {
                        // Sélectionner 3 utilisateurs au hasard
                        $randomUsers = array_slice($users, rand(0, count($users) - 3), 3);
                        echo "<td class='$cellClass'>";
                        if ($cellClass === "highlight") {
                            echo "<ul class='user-list'>";
                            foreach ($randomUsers as $user) {
                                echo "<li>$user";
                                if ($user === $currentUser) {
                                    echo "<li style=\"background-color:yellow;\">$user";
                                    echo " <button class='action-button remove-user'><i class='bi bi-trash'></i></button>";
                                }
                                echo "</li>";
                            }
                            echo "</ul>";
                        }
                        echo "</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


