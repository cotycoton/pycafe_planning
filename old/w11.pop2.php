<?php
// Récupérer le numéro de semaine depuis le paramètre URL

$date_now = new DateTime("NOW");
$date_ref = new DateTime("2024-01-01");
$week_diff = $date_now->diff($date_ref)->days/7;
$week_default=ceil($week_diff);
echo $week_now;
$weekNumber = isset($_GET['week']) ? (int)$_GET['week'] : $week_default;
$smax=152;
// Validation du numéro de semaine
if ($weekNumber < 1 || $weekNumber > $smax) {
    die("Numéro de semaine invalide. Veuillez fournir un numéro entre 1 et 52.");
}

// Année cible (2024)
$yearL=2024;

// Calculer la date du lundi de la semaine donné
//$timestamp = $date->setISODate($year, 1, $weekNumber);
//echo "UU" . "<p>";
//$timestamp = strtotime("$year-W$weekNumber-1");


//echo $timestamp . "<p>" . $weekNumber;
// Obtenir les dates des jours de la semaine (lundi à dimanche)
$daysOfWeek = [];
for ($i = 0; $i < 7; $i++) {
    //$day = date('d-m-Y', strtotime("+$i day", $timestamp));
    $week_day = new DateTime();
    $week_day->setISODate($yearL, $weekNumber,$i+1);
    $day=$week_day->format('d-m-Y');
    $year=$week_day->format('Y');
    $daysOfWeek[] = $day;
    $week=$week_day->format('W');
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

$jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];

// Couleurs pour certaines cellules
$highlightedCells = [
    ["day" => 0, "times" => ["15h30-17h30", "17h30-19h30"]], // Lundi
    ["day" => 2, "times" => $timeSlots], // Mercredi (toutes les plages horaires)
    ["day" => 4, "times" => ["15h30-17h30", "17h30-19h30"]], // Vendredi
    ["day" => 5, "times" => ["8h10-10h30", "10h30-12h30"]] // Samedi
];

// Précédent et suivant
$prevWeek = $weekNumber > 1 ? $weekNumber - 1 : $smax;
$nextWeek = $weekNumber < $smax ? $weekNumber + 1 : 1;

// Date actuelle
$currentDate = date('d-m-Y');

// Liste des utilisateurs
$users = ["user1", "user2", "user3", "user4", "user5", "user6", "user7", "user8", "user9", "user10"];
$users = ["user1", "user2", "user3", "user4", "user5", "user6", "user7", "user8", "user9", "user10","users11","users12","users13","users14"];

$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];


$currentUser = "admin";
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
            //background-color: #ffeb3b;
            background-color: #dcedc8;
            cursor: pointer;
        }
        .selected {
            background-color: #aed581 !important;
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
            cursor: progress;
        }
        .add-user {
            font-weight: bold;
            color: #007bff;
            background-color: #fff59d;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .current-user-add {
            font-weight: bold;
            color: #007bff;
            background-color: #fff59d;
            border-radius: 4px;
        }

        .current-user {
            font-weight: bold;
            color: #007bff;
            background-color: #fff59d;
            padding: 2px 4px;
            border-radius: 4px;
        }
        .hidden-button {
            //visibility: hidden;
            display: none;
        }
        .visible-button {
            //visibility: visible;
            display: inline;
        }
        
	.visible-option {
            display: inline;
        }
	.hidden-option {
            display: none;
        }

        body { font-family: Arial, sans-serif; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modalDelete {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
        }
        .modal-header { font-weight: bold; margin-bottom: 15px; }
        .modal-footer { margin-top: 15px; text-align: right; }
        .modal-footer button { margin-left: 10px; }

    </style>
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const cells = document.querySelectorAll('.highlight');

            cells.forEach(cell => {
                cell.addEventListener('click', () => {
                    console.log('click highlight');
                    // Retirer la sélection des autres cellules
                    document.querySelectorAll('.selected').forEach(selectedCell => {
                        selectedCell.classList.remove('selected');
                        const trashButtons = selectedCell.querySelectorAll('.remove-user');
                        trashButtons.forEach(button => button.classList.remove('visible-button'));
                        trashButtons.forEach(button => button.classList.add('hidden-button'));
                        
			const addButtons = selectedCell.querySelectorAll('.add-user');
                        addButtons.forEach(button => button.classList.remove('visible-button'));
                        addButtons.forEach(button => button.classList.add('hidden-button'));
                    });

                    // Ajouter la classe selected à la cellule cliquée
                    cell.classList.add('selected');
                    const trashButtons = cell.querySelectorAll('.remove-user');
		    // affichage bouton trash
                    console.log('trashButtons',trashButtons);
                    trashButtons.forEach(button => button.classList.remove('hidden-button'));
                    trashButtons.forEach(button => button.classList.add('visible-button'));

		    //creation bouton ajouter
                    const addButtons = cell.querySelectorAll('.add-user');
                    console.log('click highlight, addButton = ',addButtons.length);
	            const userLists = cell.querySelectorAll('.user-list');
	            const currentUsers = cell.querySelectorAll('.current-user');
                    console.log('click highlight, userLists = ',userLists.length);
	            if (userLists.length == 1)
			userList = userLists[0];
		    //const userList = event.target.closest('.user-list');
                    if ((addButtons.length == 0) &&  (userLists.length == 1))
		    {
			    console.log("ajout du bouton ajouter");
			    let addButton;
			    //const addButton = document.createElement('button');
			    addButton = document.createElement('button');
    			    addButton.innerHTML = '<div>Ajouterr&nbsp;&nbsp;&nbsp<i class="bi bi-clipboard-plus"></i></div>';
			    addButton.className = 'action-button add-user';
			    addButton.addEventListener('click', () => {
					    console.log('click current-user ajouter');
					    const mydiv = document.getElementById('myDiv');
					    const currentUser = mydiv.getAttribute('data-param');
					    isAdmin = (currentUser == "admin");
					    let users_yet = [];
					    let users = <?php echo json_encode($users); ?>;
					    if (isAdmin) {

					    	const selectedElement = document.querySelector('.selected');
						if (selectedElement) {
						// Récupère toutes les balises avec la classe "user" à l'intérieur de l'élément sélectionné
						const users = selectedElement.querySelectorAll('.user');

						// Affiche chaque utilisateur trouvé dans la console
						console.log("build users_yet");
						users.forEach(user => {
								users_yet.push(user.textContent);
								console.log(user.textContent); // Affiche le contenu texte de chaque utilisateur
								});
						}

						console.log("users :");
						console.log(users);
						
						console.log("users_yet :");
						console.log(users_yet);

						const availableUsers = users.filter(user => !users_yet.includes(user));
						console.log("available users :");
						console.log(availableUsers); // Affiche le contenu texte de chaque utilisateur

					    	const combo_liste = document.getElementById('userSelect');
						const users_list = combo_liste.querySelectorAll('.user-poplist');
						users_list.forEach(user => {
								console.log("users poplist"+user.textContent);
								if (availableUsers.includes(user.textContent))
								{
									user.classList.remove("hidden-option");
									user.classList.add("visible-option");
								}
								else
								{
									user.classList.remove("visible-option");
									user.classList.add("hidden-option");
								}
								});

						const visibleOption = Array.from(combo_liste.options).find(option => {
								return window.getComputedStyle(option).display !== "none"; // Vérifie le style calculé
								});

						// Définir cet élément comme sélectionné
						if (visibleOption) {
							visibleOption.selected = true;
						}


					    	const modal = document.getElementById('userModal');
						modal.style.display = 'block';
					    }
					    else
					    {
					    	const userItem = document.createElement('li');
						const userDiv = document.createElement('div');


						userDiv.classList.add('user');
						userDiv.classList.add('current-user');
						userToAdd = currentUser;

						userDiv.textContent = userToAdd;
						userItem.appendChild(userDiv);
						//userItem.textContent = currentUser;
						const removeButton = document.createElement('button');
						removeButton.innerHTML = '<i class="bi bi-trash"></i>';
						removeButton.className = 'action-button remove-user';
					    
						const mydiv = document.getElementById('myDiv');
						const currentUser = mydiv.getAttribute('data-param');
					    	isAdmin = (currentUser == "admin");
						if (!isAdmin)
							removeButton.addEventListener('click', () => removeButton.closest('li').remove());
						else
							removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');
						//userItem.appendChild(removeButton);
						userDiv.appendChild(removeButton);
						userList.appendChild(userItem);
						addButton.classList.remove('visible-button');
						addButton.classList.add('hidden-button');
						//addButton.style.visibility='hidden';
						//addButton.remove();
					    }
			    });
			    addButton.classList.remove('visible-button');
			    addButton.classList.add('hidden-button');
		            const liItem = document.createElement('li');
		            const divItem = document.createElement('div');
			    divItem.appendChild(addButton);
			    divItem.classList.add('current-user-add');
			    divItem.classList.add('user');
			    liItem.appendChild(divItem);
                            userList.appendChild(liItem);
		    }
		    if (currentUsers.length == 0)
                    {
	            	    addButton = userList.querySelectorAll('.add-user')[0];
			    addButton.classList.remove('hidden-button');
			    addButton.classList.add('visible-button');
                    }

                });
            });

            document.body.addEventListener('click', (event) => {
                if (event.target.classList.contains('current-user')) {
	            const parent = event.target.parentElement;
                    if ( !parent.classList.contains('selected'))
                    {
			return;
                    }
                    const userList = event.target.closest('.user-list');
                    const addButtons = event.target.querySelectorAll('.add-user');
                    console.log('click highlight avec current-user, addButton',addButtons.length);
                    if (addButtons.length == 0)
		    {
                        const addButton = document.createElement('button');
                        addButton.textContent = 'Ajouter';
                        addButton.className = 'action-button add-user';
                        addButton.addEventListener('click', () => {
                        	console.log('click current-user ajouter');
    		         	const userItem = document.createElement('li');
    			        const userDiv = document.createElement('div');
        
        			const mydiv = document.getElementById('myDiv');
        			const currentUser = mydiv.getAttribute('data-param');
        
        			userDiv.classList.add('current-user');
    				userDiv.textContent = currentUser;
   	 			userItem.appendChild(userDiv);
    				const removeButton = document.createElement('button');
    				removeButton.innerHTML = '<i class="bi bi-trash"></i>';
    				removeButton.className = 'action-button remove-user';
				if (!isAdmin)
					removeButton.addEventListener('click', () => removeButton.closest('li').remove());
				else			
					removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');
    				userDiv.appendChild(removeButton);
    				userList.appendChild(userItem);
    				addButton.classList.remove('visible-button');
    				addButton.classList.add('hidden-button');
    			});
			userList.appendChild(addButton);
			console.log("ajout du bouton ajouter depuis current-user");
    		    }
                    event.target.closest('li').remove();
                }
            });


            document.body.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-user')) {
//                    const userList = event.target.closest('.user-list');
//                    const addButton = document.createElement('button');
//                    addButton.textContent = 'Ajouter';
//                    addButton.className = 'action-button add-user';
//                    addButton.addEventListener('click', () => {
//                        const userItem = document.createElement('li');
//                        userItem.textContent = 'user1';
//                        const removeButton = document.createElement('button');
//                        removeButton.inn	erHTML = '<i class="bi bi-trash"></i>';
//                        removeButton.className = 'action-button remove-user';
//                        removeButton.addEventListener('click', () => 
//			{
//                           removeButton.closest('li').remove()
//                           addButton.classList.remove('hidden-button');
//			   addButton.classList.add('visible-button');
//			});
//                        userItem.appendChild(removeButton);
//                        userList.appendChild(userItem);
//			addButton.classList.remove('visible-button');
//			addButton.classList.add('hidden-button');
//                    });
//                    userList.appendChild(addButton);
                    event.target.closest('li').remove();
                }
            });
        });
    </script>
</head>
<body>
    <div id="myDiv" data-param="<?php echo htmlspecialchars($currentUser); ?>"></div>
    <div class="nav-arrows">
        <a href="?week=<?php echo $prevWeek; ?>" title="Semaine précédente">&#8592;</a>
        <a href="?week=<?php echo $nextWeek; ?>" title="Semaine suivante">&#8594;</a>
    </div>

    <h1>Tableau de la Semaine <?php echo $week; ?> (Année <?php echo $year; ?>)</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <?php
                foreach ($daysOfWeek as $index => $day) {
                    $dtime = DateTime::createFromFormat('d-m-Y', $day);
                    $class = ($day === $currentDate) ? "today" : "";
                    echo "<th class='$class'>" . $jours[$dtime->format('w')] . "<br>" . $day . "</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($timeSlots as $index => $timeSlot) {
                echo "<tr id=\"$timeSlot\">";
                echo "<td id=\"col0\">" . $timeSlot . "</td>";
                for ($col = 0; $col < 7; $col++) {
                    $jour = $jours[$col];
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
                        echo "<td id=\"$jour\" class='$cellClass'>";
                        if ($cellClass === "highlight") {
                            echo "<ul class='user-list'>";
                            foreach ($randomUsers as $user) {
				$isAdmin = $currentUser == "admin";
                                if ($user == $currentUser) {
                                    echo "<li><div class=\"current-user\">$user";
                                    echo "<button onclick=\"removeItem(this)\" class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "</div>";
                                }
				elseif ($isAdmin) {
                                    echo "<li><div class=\"user\">$user";
                                    //echo "<button onclick=\"removeItemAdmin(this)\"  class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "<button onclick=\"removeItemAdmin(this,'" .$user . "')\"  class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "</div>";
                                }
				else
				{
                                    echo "<li>$user";
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

    <!-- Fenêtre modale -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Sélectionnez un utilisateur</div>
            <div>
                <select id="userSelect">
                    <?php foreach ($users as $user): ?>
                        <option class="user-poplist visible-option" value="<?php echo $user; ?>"><?php echo $user; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button id="cancelBtn">Annuler</button>
                <button id="okBtn">OK</button>
            </div>
        </div>
    </div>

    <!-- Fenêtre modaleDelete -->
    <div id="userModalDelete" class="modalDelete" data-param="user">
        <div class="modal-content">
            <div class="modal-header">Sélectionnez un utilisateur</div>
            <div class="modal-footer">
                <button id="cancelBtnDelete">Annuler</button>
                <button id="okBtnDelete">OK</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('userModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const okBtn = document.getElementById('okBtn');
        const userSelect = document.getElementById('userSelect');

	const modalDelete = document.getElementById('userModalDelete');
        const cancelBtnDelete = document.getElementById('cancelBtnDelete');
        const okBtnDelete = document.getElementById('okBtnDelete');


	function removeItem(button) {
		const li = button.closest('li'); // Trouve le parent <li> le plus proche
		if (li) {
			li.remove(); // Supprime le <li>
		}
	}

	function removeItemAdmin(button,user) {

		console.log("Validation de la suppresion " + user);
		modalDelete.style.display = 'inline';
		header = modalDelete.querySelector(".modal-header");
		header.textContent = "Supprimer " + user;

		modalDelete.dataset.param = user;
	}


        // Fermer la modale sur Annuler
        cancelBtn.onclick = function () {
            modal.style.display = 'none';
            console.log(null); // Retourne null
        };

        // Retourner l'utilisateur sélectionné sur OK
        okBtn.onclick = function () {
            const selectedUser = userSelect.value;
	    userToAdd = selectedUser;
            modal.style.display = 'none';
            console.log(selectedUser); // Retourne l'utilisateur sélectionné



	    const userItem = document.createElement('li');
	    const userDiv = document.createElement('div');
            userDiv.classList.add('user');

	    const div = document.getElementById('myDiv');
	    const currentUser = div.getAttribute('data-param');

	    isAdmin = (currentUser == "admin");
	    if (! isAdmin)
		    userDiv.classList.add('current-user');
	    

	    userDiv.textContent = userToAdd;
	    userItem.appendChild(userDiv);
	    //userItem.textContent = currentUser;
	    //if (! isAdmin)
	    {
		    const removeButton = document.createElement('button');
		    removeButton.innerHTML = '<i class="bi bi-trash"></i>';
		    removeButton.className = 'action-button remove-user';
						
		    if (!isAdmin)
			    removeButton.addEventListener('click', () => removeButton.closest('li').remove());
		    else
			    removeButton.addEventListener('click', () => removeItemAdmin(removeButton,userToAdd));
			    //removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');

		    userDiv.appendChild(removeButton);
	    }
	    userList.appendChild(userItem);

	    const parentLi = addButton.closest('li');
	    console.log(parentLi);
	    console.log(parentLi.textContent);
	    parentLi.remove();
	    //addButton.classList.remove('visible-button');
	    //addButton.classList.add('hidden-button');



        };
	

        // Fermer la modale sur Annuler
        cancelBtnDelete.onclick = function () {
            modalDelete.style.display = 'none';
            console.log(null); // Retourne null
        };

        // Retourner l'utilisateur sélectionné sur OK
        okBtnDelete.onclick = function () {

		modalDelete.style.display = 'none';

		const userToDelete = modalDelete.dataset.param
		const selectedElement = document.querySelector('.selected');
		const users = selectedElement.querySelectorAll('.user');
		users.forEach(user => 
			{
				if (user.textContent == userToDelete)
				{
					user.closest('li').remove();
				}
			});
		console.log("Suppresion de " + userToDelete);
        };


        // Fermer la modale si l'utilisateur clique en dehors
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
            else if (event.target === modalDelete) {
                modalDelete.style.display = 'none';
            }
        };
        

    </script>

</body>
</html>



