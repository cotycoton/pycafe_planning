<?php

require 'database.php'; // Connexion à la base de données

session_start();
try {
    // Récupérer tous les utilisateurs sauf ceux avec le rôle 'admin'
    $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, phone FROM users WHERE role != 'admin'");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Définir les en-têtes des colonnes
    $headers = ['ID', 'Prénom', 'Nom', 'Email', 'Téléphone'];

    // Tableau pour stocker les données des utilisateurs
    $userData = [];

    // Tableau pour stocker les prénoms suivis des deux premières lettres du nom
    $nameAbbreviations = [];

    // Remplir les tableaux avec les données récupérées
    foreach ($users as $user) {
        $userData[] = [
            'id' => htmlspecialchars($user['id']),
            'prenom' => htmlspecialchars($user['firstname']),
            'nom' => htmlspecialchars($user['lastname']),
            'email' => htmlspecialchars($user['email']),
            'telephone' => htmlspecialchars($user['phone'])
        ];

        $nameAbbreviations[] = htmlspecialchars($user['firstname']) . ' ' . ucfirst(substr($user['lastname'], 0, 2)) . '.';
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$admin=0;
if (isset($_SESSION['user_id'])) 
{
	$connected = 1;
	if ($_SESSION['user_role']=="admin")
	       $admin = 1;	
} else 
{
	$connected = 0;
}



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
    "17h30-19h30",
    "19h30-22h00"
];

$jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];

// Couleurs pour certaines cellules
$highlightedCells = [
    ["day" => 0, "times" => ["15h30-17h30", "17h30-19h30"]], // Lundi
    ["day" => 2, "times" => ["8h10-10h30", "10h30-12h30","15h30-17h30", "17h30-19h30"]],//$timeSlots], // Mercredi (toutes les plages horaires)
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
$users =$nameAbbreviations;

$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];


$currentUser = $_SESSION['user_abb'];
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
	h1, h2 {
		text-align: center;

        } 
        table {
            border-collapse: collapse;
            width: 100%;
	    margin: 20px auto;
	    //table-layout: fixed;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
	    text-align: center;
	    width: 12vw;
	}
	.col_1st{
	    width: 5.5vw;
	}
	.col_jour{
	    width: 13.5vw;
	}
	
        .cowork {
	}

        th {
            background-color: #f0f0f0;
        }
        .highlight {
            background-color: #dcedc8;
            cursor: pointer;
        }
        .not_open {
            background-color: #ffffff;
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
            width: 350px;
        }
        .modal-header { font-weight: bold; margin-bottom: 15px; }
        .modal-footer { margin-top: 15px; text-align: right; }
        .modal-footer button { margin-left: 10px; }

    </style>
    <script>

	// Initialisation de la variable
        let editMode = 0;

	function cleanSelected()
	{
		document.querySelectorAll('.selected').forEach(selectedCell => {
		selectedCell.classList.remove('selected');
		
		const trashButtons = selectedCell.querySelectorAll('.remove-user');
		trashButtons.forEach(button => button.classList.remove('visible-button'));
		trashButtons.forEach(button => button.classList.add('hidden-button'));

		const addButtons = selectedCell.querySelectorAll('.add-user');
		addButtons.forEach(button => button.classList.remove('visible-button'));
		addButtons.forEach(button => button.classList.add('hidden-button'));
		});
	}
	
	function displaySelected(cell)
	{
		const mydiv = document.getElementById('myDiv');
		const currentUser = mydiv.getAttribute('data-param');
		const connected = mydiv.getAttribute('data-connected');
		const admin = mydiv.getAttribute('data-admin');
		isAdmin = (admin == "1") && (connected == "1");

		console.log('admin ',admin," connected ", connected, " isAdmin ",isAdmin);

		const trashButtons = cell.querySelectorAll('.remove-user');
		console.log('trashButtons',trashButtons,trashButtons.length);

		trashButtons.forEach(button => button.classList.remove('hidden-button'));

		trashButtons.forEach
		(
			button => 
			{
				// Récupérer le texte du parent de l'élément
				const parentText = button.parentNode.textContent.trim();
				// Vérifier si admin est égal à 1 ou si le texte du parent est 'user'
				if ((isAdmin) || (parentText === currentUser)) 
				{
					button.classList.remove('hidden-button');
					button.classList.add('visible-button');
					console.log("set button " + parentText + " visible");
				}
				else
				{
					console.log("set button " + parentText + " hidden");
					button.classList.remove('visible-button');
					button.classList.add('hidden-button');
				}
			}
		);

		document.querySelectorAll('.selected').forEach
		(
			selectedCell => 
			{
				const addButtons = selectedCell.querySelectorAll('.add-user');
				addButtons.forEach(button => button.classList.add('visible-button'));
				addButtons.forEach(button => button.classList.remove('hidden-button'));

			}
		);
	}


	//creation bouton ajouter
	function addButtonUser(cell)
	{
		const mydiv = document.getElementById('myDiv');
		const currentUser = mydiv.getAttribute('data-param');
		const connected = mydiv.getAttribute('data-connected');
		const admin = mydiv.getAttribute('data-admin');
		isAdmin = (admin == "1") && (connected == "1");

		const addButtons = cell.querySelectorAll('.add-user');
		console.log('click highlight, addButton = ',addButtons.length);
		const userLists = cell.querySelectorAll('.user-list');
		const currentUsers = cell.querySelectorAll('.current-user');
		console.log('click highlight, userLists = ',userLists.length);
		if (userLists.length == 1)
			userList = userLists[0];

		let users_yet = [];
		let users_all = <?php echo json_encode($users); ?>;
		const users = cell.querySelectorAll('.user, .current-user');
		users.forEach
		(
			user => 
			{
				users_yet.push(user.textContent);
				console.log(user.textContent); // Affiche le contenu texte de chaque utilisateur
			}
		);
		if ( !isAdmin)
			users_all = [currentUser];

		const availableUsers = users_all.filter(user => !users_yet.includes(user));
		
		console.log("users_all :");
		console.log(users_all);

		console.log("users :");
		console.log(users);

		console.log("users_yet :");
		console.log(users_yet);

		console.log("available users :");
		console.log(availableUsers); // Affiche le contenu texte de chaque utilisateur

		if ((addButtons.length == 0) &&  (userLists.length == 1) && (availableUsers.length >0))
		{
			console.log("creation du bouton ajouter");
			let addButton;
			//const addButton = document.createElement('button');
			addButton = document.createElement('button');
			addButton.innerHTML = '<div>Ajouterr&nbsp;&nbsp;&nbsp<i class="bi bi-clipboard-plus"></i></div>';
			addButton.className = 'action-button add-user';
			addButton.addEventListener('click', () => {
			console.log('click current-user ajouter');
			//const mydiv = document.getElementById('myDiv');
			//const currentUser = mydiv.getAttribute('data-param');
			//const connected = mydiv.getAttribute('data-connected');
			//const admin = mydiv.getAttribute('data-admin');
			//isAdmin = (admin == "1") && (connected == "1");
			//let users_yet = [];
			//let users_all = <?php echo json_encode($users); ?>;

			// necessite etre connecter
			if (connected == "1") 
			{
				console.log("connectec = 1");
				//const selectedElement = document.querySelector('.selected');
				//if (selectedElement) 
				//{
				//	// Récupère toutes les balises avec la classe "user" à l'intérieur de l'élément sélectionné
				//	const users = selectedElement.querySelectorAll('.user');

				//	// Affiche chaque utilisateur trouvé dans la console
				//	console.log("build users_yet");
				//	users.forEach
				//	(
				//		user => 
				//		{
				//			users_yet.push(user.textContent);
				//			console.log(user.textContent); // Affiche le contenu texte de chaque utilisateur
				//		}
				//	);
				//}
				//const availableUsers = users_all.filter(user => !users_yet.includes(user));

				console.log("users :");
				console.log(users);

				console.log("users_yet :");
				console.log(users_yet);

				console.log("available users :");
				console.log(availableUsers); // Affiche le contenu texte de chaque utilisateur

				const combo_liste = document.getElementById('userSelect');
				const users_list = combo_liste.querySelectorAll('.user-poplist');
				console.log("MAJ users poplist ");
				users_list.forEach
				(
					user => 
					{
						if (availableUsers.includes(user.textContent))
						{
							user.classList.remove("hidden-option");
							user.classList.add("visible-option");
							console.log("users poplist "+user.textContent + " set visible");
						}
						else
						{
							user.classList.remove("visible-option");
							user.classList.add("hidden-option");
							console.log("users poplist "+user.textContent + " set hidden");
						}
					}
				);

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

			});
			addButton.classList.remove('visible-button');
			addButton.classList.add('hidden-button');
			const liItem = document.createElement('li');
			const divItem = document.createElement('div');
			divItem.appendChild(addButton);
			divItem.classList.add('current-user-add');
			//divItem.classList.add('user');
			liItem.appendChild(divItem);
			userList.appendChild(liItem);
		}
		if (currentUsers.length == 0)
		{
			const addButtons = userList.querySelectorAll('.add-user');//[0];
			if (addButtons.length >0)
			{
				addButton = addButtons[0];
				addButton.classList.remove('hidden-button');
				addButton.classList.add('visible-button');
			}
		}

	}


        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('#tableContainer');
	    console.log(container);

	    container.addEventListener('click', (event) => {
	    // Vérifier si l'élément cliqué a la classe .highlight
		console.log(event.target.classList);
		if (editMode == 1)
		{
			console.log('Cellule cliquée :', event.target.textContent);
			cell = event.target;
	        	if (event.target.classList.contains('not_open')) 
			{
                            	cell.classList.remove("not_open");
                            	cell.classList.add("highlight");
                        }
			else if (event.target.classList.contains('highlight')) 
			{
				cell.classList.remove("highlight");
				cell.classList.add("not_open");
			}
			else if (event.target.classList.contains('user')) 
			{
				const parent = cell.closest('.user-list');
				const parent2 = parent.parentNode;
				if (parent2.classList.contains('not_open'))
				{
					parent2.classList.remove("not_open");
					parent2.classList.add("highlight");
				}
				else
				{
					parent2.classList.remove("highlight");
					parent2.classList.add("not_open");
				}
			}


		}
		else
		{
			console.log('Cellule cliquée :', event.target.textContent);
			cell = event.target;
			if (event.target.classList.contains('highlight')) 
			{
				console.log('click highlight');
				// Retirer la sélection des autres cellules
				cleanSelected();
    
				// Ajouter la classe selected à la cellule cliquée
				cell.classList.add('selected');
				
				displaySelected(cell);
				addButtonUser(cell);


			 }
			 else if ( (event.target.classList.contains('user')) || (event.target.classList.contains('current-user')) ) 
			 {
				cell = event.target;
				console.log(cell.textContent);
				const parent = cell.closest('.user-list');
				const parent2 = parent.parentNode;

				cleanSelected();

				if (parent2.classList.contains('not_open'))
					return;

				// Ajouter la classe selected à la cellule cliquée
				parent2.classList.add("selected");
				
				displaySelected(parent2);
				addButtonUser(parent2);
				 
			 }
		}


	    });
	});
//
//	document.addEventListener('DOMContentLoaded', () => 
//	{
//            const cells = document.querySelectorAll('.highlight');
//
//            cells.forEach(cell => {
//                cell.addEventListener('click', () => {
//		
//		    if (editMode == 1)
//                    {
//                            cell.classList.remove("highlight");
//                            cell.classList.add("not_open");
//                    }
//		    else
//		    {
//			    console.log('click highlight');
//			    // Retirer la sélection des autres cellules
//			    document.querySelectorAll('.selected').forEach(selectedCell => {
//			    selectedCell.classList.remove('selected');
//			    const trashButtons = selectedCell.querySelectorAll('.remove-user');
//			    trashButtons.forEach(button => button.classList.remove('visible-button'));
//			    trashButtons.forEach(button => button.classList.add('hidden-button'));
//
//			    const addButtons = selectedCell.querySelectorAll('.add-user');
//			    addButtons.forEach(button => button.classList.remove('visible-button'));
//			    addButtons.forEach(button => button.classList.add('hidden-button'));
//			    });
//
//			    // Ajouter la classe selected à la cellule cliquée
//			    cell.classList.add('selected');
//			    const trashButtons = cell.querySelectorAll('.remove-user');
//			    // affichage bouton trash
//			    const mydiv = document.getElementById('myDiv');
//			    const currentUser = mydiv.getAttribute('data-param');
//			    const connected = mydiv.getAttribute('data-connected');
//			    const admin = mydiv.getAttribute('data-admin');
//			    isAdmin = (admin == "1") && (connected == "1");
//
//			    console.log('admin ',admin," connected ", connected, " isAdmin ",isAdmin);
//			    console.log('trashButtons',trashButtons);
//
//			    trashButtons.forEach(button => button.classList.remove('hidden-button'));
//			    trashButtons.forEach(button => {
//			    // Récupérer le texte du parent de l'élément
//			    const parentText = button.parentNode.textContent.trim();
//			    // Vérifier si admin est égal à 1 ou si le texte du parent est 'user'
//			    if ((isAdmin) || (parentText === currentUser)) {
//				    button.classList.add('visible-button');
//				    console.log("set button " + parentText + " visible");
//			    }
//			    else
//			    {
//				    console.log("set button " + parentText + " hidden");
//				    button.classList.add('hidden-button');
//			    }
//			    });
//
//			    //creation bouton ajouter
//			    const addButtons = cell.querySelectorAll('.add-user');
//			    console.log('click highlight, addButton = ',addButtons.length);
//			    const userLists = cell.querySelectorAll('.user-list');
//			    const currentUsers = cell.querySelectorAll('.current-user');
//			    console.log('click highlight, userLists = ',userLists.length);
//			    if (userLists.length == 1)
//				    userList = userLists[0];
//
//			    let users_yet = [];
//			    let users_all = <?php echo json_encode($users); ?>;
//			    const users = cell.querySelectorAll('.user, .current-user');
//			    users.forEach(user => {
//			    users_yet.push(user.textContent);
//			    console.log(user.textContent); // Affiche le contenu texte de chaque utilisateur
//			    });
//			    const availableUsers = users_all.filter(user => !users_yet.includes(user));
//
//			    console.log("users :");
//			    console.log(users);
//
//			    console.log("users_yet :");
//			    console.log(users_yet);
//
//			    console.log("available users :");
//			    console.log(availableUsers); // Affiche le contenu texte de chaque utilisateur
//
//
//
//			    //const userList = event.target.closest('.user-list');
//			    if ((addButtons.length == 0) &&  (userLists.length == 1) && (availableUsers.length >0))
//			    {
//				    console.log("creation du bouton ajouter");
//				    let addButton;
//				    //const addButton = document.createElement('button');
//				    addButton = document.createElement('button');
//				    addButton.innerHTML = '<div>Ajouterr&nbsp;&nbsp;&nbsp<i class="bi bi-clipboard-plus"></i></div>';
//				    addButton.className = 'action-button add-user';
//				    addButton.addEventListener('click', () => {
//				    console.log('click current-user ajouter');
//				    const mydiv = document.getElementById('myDiv');
//				    const currentUser = mydiv.getAttribute('data-param');
//				    const connected = mydiv.getAttribute('data-connected');
//				    const admin = mydiv.getAttribute('data-admin');
//				    isAdmin = (admin == "1") && (connected == "1");
//				    let users_yet = [];
//				    let users_all = <?php echo json_encode($users); ?>;
//				    if (connected == "1") {
//					    console.log("connectec = 1");
//					    const selectedElement = document.querySelector('.selected');
//					    if (selectedElement) {
//						    // Récupère toutes les balises avec la classe "user" à l'intérieur de l'élément sélectionné
//						    const users = selectedElement.querySelectorAll('.user');
//
//						    // Affiche chaque utilisateur trouvé dans la console
//						    console.log("build users_yet");
//						    users.forEach(user => {
//						    users_yet.push(user.textContent);
//						    console.log(user.textContent); // Affiche le contenu texte de chaque utilisateur
//						    });
//					    }
//					    const availableUsers = users_all.filter(user => !users_yet.includes(user));
//
//					    console.log("users :");
//					    console.log(users);
//
//					    console.log("users_yet :");
//					    console.log(users_yet);
//
//					    console.log("available users :");
//					    console.log(availableUsers); // Affiche le contenu texte de chaque utilisateur
//
//					    const combo_liste = document.getElementById('userSelect');
//					    const users_list = combo_liste.querySelectorAll('.user-poplist');
//					    console.log("MAJ users poplist ");
//					    users_list.forEach(user => {
//					    if (availableUsers.includes(user.textContent))
//					    {
//						    user.classList.remove("hidden-option");
//						    user.classList.add("visible-option");
//						    console.log("users poplist "+user.textContent + " set visible");
//					    }
//					    else
//					    {
//						    user.classList.remove("visible-option");
//						    user.classList.add("hidden-option");
//						    console.log("users poplist "+user.textContent + " set hidden");
//					    }
//					    });
//
//					    const visibleOption = Array.from(combo_liste.options).find(option => {
//					    return window.getComputedStyle(option).display !== "none"; // Vérifie le style calculé
//					    });
//
//					    // Définir cet élément comme sélectionné
//					    if (visibleOption) {
//						    visibleOption.selected = true;
//					    }
//
//
//					    const modal = document.getElementById('userModal');
//					    modal.style.display = 'block';
//				    }
//				    else if (0 == 1)// todelete
//				    {
//					    const userItem = document.createElement('li');
//					    const userDiv = document.createElement('div');
//
//
//					    userDiv.classList.add('user');
//					    userDiv.classList.add('current-user');
//					    userToAdd = currentUser;
//
//					    userDiv.textContent = userToAdd;
//					    userItem.appendChild(userDiv);
//					    //userItem.textContent = currentUser;
//					    const removeButton = document.createElement('button');
//					    removeButton.innerHTML = '<i class="bi bi-trash"></i>';
//					    removeButton.className = 'action-button remove-user';
//
//					    const mydiv = document.getElementById('myDiv');
//					    const currentUser = mydiv.getAttribute('data-param');
//					    const admin = mydiv.getAttribute('data-admin');
//					    isAdmin = (admin == "1");
//					    //if (!isAdmin)
//					    //	removeButton.addEventListener('click', () => removeButton.closest('li').remove());
//					    //else
//					    //	removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');
//					    //removeButton.addEventListener('click', () => removeItemAdmin(removeButton,userToAdd));
//					    //userItem.appendChild(removeButton);
//					    userDiv.appendChild(removeButton);
//					    userList.appendChild(userItem);
//					    addButton.classList.remove('visible-button');
//					    addButton.classList.add('hidden-button');
//					    removeButton.setAttribute('onclick', "removeItemAdmin(this, '"+userToAdd+ "')");
//					    //removeButton.addEventListener('click', () => removeItemAdmin(removeButton,userToAdd));
//					    //addButton.style.visibility='hidden';
//					    //addButton.remove();
//				    }
//				    });
//				    addButton.classList.remove('visible-button');
//				    addButton.classList.add('hidden-button');
//				    const liItem = document.createElement('li');
//				    const divItem = document.createElement('div');
//				    divItem.appendChild(addButton);
//				    divItem.classList.add('current-user-add');
//				    //divItem.classList.add('user');
//				    liItem.appendChild(divItem);
//				    userList.appendChild(liItem);
//			    }
//			    if (currentUsers.length == 0)
//			    {
//				    const addButtons = userList.querySelectorAll('.add-user');//[0];
//				    if (addButtons.length >0)
//				    {
//					    addButton = addButtons[0];
//					    addButton.classList.remove('hidden-button');
//					    addButton.classList.add('visible-button');
//				    }
//			    }
//		    }
//                });
//            });
//
//            document.body.addEventListener('click', (event) => {
//                if (event.target.classList.contains('current-user')) {
//	            const parent = event.target.parentElement;
//                    if ( !parent.classList.contains('selected'))
//                    {
//			return;
//                    }
//                    const userList = event.target.closest('.user-list');
//                    const addButtons = event.target.querySelectorAll('.add-user');
//                    console.log('click highlight avec current-user, addButton',addButtons.length);
//                    if (addButtons.length == 0)
//		    {
//                        const addButton = document.createElement('button');
//                        addButton.textContent = 'Ajouter';
//                        addButton.className = 'action-button add-user';
//                        addButton.addEventListener('click', () => {
//                        	console.log('click current-user ajouter');
//    		         	const userItem = document.createElement('li');
//    			        const userDiv = document.createElement('div');
//        
//        			const mydiv = document.getElementById('myDiv');
//        			const currentUser = mydiv.getAttribute('data-param');
//        			const admin = mydiv.getAttribute('data-admin');
//        
//        			userDiv.classList.add('current-user');
//    				userDiv.textContent = currentUser;
//   	 			userItem.appendChild(userDiv);
//    				const removeButton = document.createElement('button');
//    				removeButton.innerHTML = '<i class="bi bi-trash"></i>';
//				removeButton.className = 'action-button remove-user';
//				isAdmin = (admin == "1");
//				//if (!isAdmin)
//				//	removeButton.addEventListener('click', () => removeButton.closest('li').remove());
//				//else			
//				//	removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');
//				removeButton.addEventListener('click', () => removeItemAdmin(removeButton,currentUser));
//    				userDiv.appendChild(removeButton);
//    				userList.appendChild(userItem);
//    				addButton.classList.remove('visible-button');
//    				addButton.classList.add('hidden-button');
//    			});
//			userList.appendChild(addButton);
//			console.log("ajout du bouton ajouter depuis current-user");
//    		    }
//                    event.target.closest('li').remove();
//                }
//            });
//
//
//            document.body.addEventListener('click', (event) => {
//                if (event.target.classList.contains('remove-user')) {
////                    const userList = event.target.closest('.user-list');
////                    const addButton = document.createElement('button');
////                    addButton.textContent = 'Ajouter';
////                    addButton.className = 'action-button add-user';
////                    addButton.addEventListener('click', () => {
////                        const userItem = document.createElement('li');
////                        userItem.textContent = 'user1';
////                        const removeButton = document.createElement('button');
////                        removeButton.inn	erHTML = '<i class="bi bi-trash"></i>';
////                        removeButton.className = 'action-button remove-user';
////                        removeButton.addEventListener('click', () => 
////			{
////                           removeButton.closest('li').remove()
////                           addButton.classList.remove('hidden-button');
////			   addButton.classList.add('visible-button');
////			});
////                        userItem.appendChild(removeButton);
////                        userList.appendChild(userItem);
////			addButton.classList.remove('visible-button');
////			addButton.classList.add('hidden-button');
////                    });
////                    userList.appendChild(addButton);
//                    event.target.closest('li').remove();
//                }
//            });
//	}
//	);
//
//	document.addEventListener('DOMContentLoaded', () => {
//               const cells = document.querySelectorAll('.not_open');
//            
//               cells.forEach(cell => {
//                        cell.addEventListener('click', () => {
//                        if (editMode == 1)
//                        {
//                            cell.classList.remove("not_open");
//                            cell.classList.add("highlight");
//                        }
//            	    });
//            	});
//        
//        });


    </script>
</head>
<body>
    <?php
        echo "<div id=\"myDiv\"";
        echo " data-param=\"" .  htmlspecialchars($currentUser) . "\"";
        echo " data-connected=\"" .  htmlspecialchars($connected) . "\"";
        echo " data-admin=\"" .  htmlspecialchars($admin) . "\"";
	echo "></div>";
    ?>
    <div class="nav-arrows">
        <a href="?week=<?php echo $prevWeek; ?>" title="Semaine précédente">&#8592;</a>
        <a href="?week=<?php echo $nextWeek; ?>" title="Semaine suivante">&#8594;</a>
    </div>

<?php
if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté
    //echo "<h1>Bienvenue " . htmlspecialchars($_SESSION['user_name']) . " sur le planning de l'epicafe!</h1>";
} else {
}
?>
    <h2>Tableau de la Semaine <?php echo $week; ?> (Année <?php echo $year; ?>)</h2>

    <table id="tableContainer">
        <thead>
            <tr>
                <th class="col_1st">#</th>
                <?php
                foreach ($daysOfWeek as $index => $day) {
                    $dtime = DateTime::createFromFormat('d-m-Y', $day);
                    $class = ($day === $currentDate) ? "today" : "";
                    echo "<th class='$class col_jour'>" . $jours[$dtime->format('w')] . "<br>" . $day . "</th>";
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
                    $cellClass = "not_open";
                    foreach ($highlightedCells as $highlight) {
                        if ($highlight["day"] == $col && in_array($timeSlot, $highlight["times"])) {
                            $cellClass = "highlight";
                            break;
                        }
                    }
                    if ($timeSlot === "") {
                        echo "<td class=\"col_jour\"></td>"; // Cellules vides pour les lignes sans plages horaires
                    } else {
                        // Sélectionner 3 utilisateurs au hasard
                        $randomUsers = array_slice($users, rand(0, count($users) - 3), 3);
                        $randomUsers = array_slice($users, rand(0, count($users) - 3), rand(0,count($users)));
                        echo "<td id=\"$jour\" class='$cellClass col_jour'>";
                        if ($cellClass === "highlight") {
                            echo "<ul class='user-list'>";
                            foreach ($randomUsers as $user) {
				$isAdmin = $admin;
                                if ($user == $currentUser) {
                                    echo "<li><div data-cowork=\"false\" class=\"current-user\">$user";
                                    echo "<button onclick=\"removeItemAdmin(this,'" .$user . "')\" class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "</div>";
                                }
				elseif ($isAdmin) {
                                    echo "<li><div  data-cowork=\"false\" class=\"user\">$user";
                                    //echo "<button onclick=\"removeItemAdmin(this)\"  class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "<button onclick=\"removeItemAdmin(this,'" .$user . "')\"  class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
                                    echo "</div>";
                                }
				else
				{
                                    echo "<li><div  data-cowork=\"false\" class=\"user\">$user";
                                    echo "</div>";
				}
                                echo "</li>";
                            }
                            echo "</ul>";
			}
			else
                            echo "<ul class='user-list'></ul>";
                        echo "</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php
if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté
	echo "<p align=\"center\">Connecté en tant que " . htmlspecialchars($_SESSION['user_name']);
        if ($_SESSION["user_role"] == "admin")
	   echo " (admin) " ;
    echo "- <a href=\"logout.php\">Déconnexion</a>";
    echo "- <a href=\"#\" id=\"toggleEditMode\" >Edit mode</a></p>";
} else {
    echo "<p align=\"center\">Accès en lecture seul, veuillez vous <a href=\"login2.php\">connecter</a></p>";

}
?>
	<script>
        // Récupération du lien par son ID
        const link = document.getElementById('toggleEditMode');

        // Ajout d'un écouteur d'événement pour le clic sur le lien
        link.addEventListener('click', function(event) {
            // Empêcher le comportement par défaut du lien
            event.preventDefault();

            // Toggle la variable entre 0 et 1
            editMode = editMode === 0 ? 1 : 0;

            // Changer le texte du lien en fonction de la valeur de la variable
            if (editMode === 1) {
                link.textContent = 'Désactiver Edit Mode';
            } else {
                link.textContent = 'Activer Edit Mode';
            }

            // Afficher la valeur de la variable dans la console (pour le débogage)
            console.log('editMode:', editMode);
        });



    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fenêtre modale -->
    <div id="userModal" class="modal">
	<div class="modal-content">
            <?php
		if ($_SESSION["user_role"]=="admin")
            		echo "<div class=\"modal-header\">Sélectionnez un utilisateur</div>";
		else
            		echo "<div class=\"modal-header\">Options</div>";
            ?>
            <div>
                <select id="userSelect">
                    <?php foreach ($users as $user): ?>
                        <option class="user-poplist visible-option" value="<?php echo $user; ?>"><?php echo $user; ?></option>
                    <?php endforeach; ?>
                </select>
	    </div>
            <label>Je souhaites assurer le service à 2
                <input id="userService" type="checkbox" name="service" value="accept">
            </label>
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
        const userService = document.getElementById('userService');

	const modalDelete = document.getElementById('userModalDelete');
        const cancelBtnDelete = document.getElementById('cancelBtnDelete');
        const okBtnDelete = document.getElementById('okBtnDelete');


	//function removeItem(button) {
	//	const li = button.closest('li'); // Trouve le parent <li> le plus proche
	//	if (li) {
	//		li.remove(); // Supprime le <li>
	//	}
	//}

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
	    const serviceUser = userService.checked;
	    console.log("service : ",selectedUser," choix ",serviceUser);
	    userToAdd = selectedUser;
            modal.style.display = 'none';
            console.log(selectedUser); // Retourne l'utilisateur sélectionné



	    const userItem = document.createElement('li');
	    const userDiv = document.createElement('div');
	    userDiv.dataset.cowork=serviceUser;
            userDiv.classList.add('user');

	    const div = document.getElementById('myDiv');
	    const currentUser = div.getAttribute('data-param');
	    const admin = div.getAttribute('data-admin');


	    isAdmin = (admin == "1");
	    if (! isAdmin)
		    userDiv.classList.add('current-user');
	    
	    userList.appendChild(userItem);
	    userItem.appendChild(userDiv);



	    userDiv.textContent = userToAdd;
	    if (serviceUser)
	    {
		    let icon = document.createElement("i");
		    icon.className = "bi bi-people-fill"; // Ajouter les classes Bootstrap Icons
		    icon.style.opacity = "1.0"; // Appliquer la transparence
		    icon.style.paddingRight = "5px";  

		    userDiv.prepend(icon);
	    }
	    //userItem.textContent = currentUser;
	    //if (! isAdmin)
	    {
		    const removeButton = document.createElement('button');
		    removeButton.innerHTML = '<i class="bi bi-trash"></i>';
		    removeButton.className = 'action-button remove-user e';
						
		    //if (!isAdmin)
		    //	    removeButton.addEventListener('click', () => removeButton.closest('li').remove());
		    //else
		    console.log("removeItemAdmin(",removeButton,userToAdd);
			    //removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');

                    const contentHtml = "<button onclick=\"removeItemAdmin(this,'" + userToAdd + "')\" class=\"action-button remove-user hidden-button\"><i class=\"bi bi-trash\"></i></button>";
		//	userDiv.innerHTML += contentHtml;
		    userDiv.appendChild(removeButton);
		    removeButton.setAttribute('onclick', "removeItemAdmin(this, '"+userToAdd+ "')");
		    //removeButton.addEventListener('click', () => removeItemAdmin(removeButton,userToAdd));
	    }
	    //userList.appendChild(userItem);

	    userDiv.parentNode.parentNode.parentNode.classList.add('cowork');

	    users = userList.querySelectorAll('.user, .current-user');
	    users.forEach(user => {
				console.log(user);
	    	});

	    console.log("nbres d'user inscrit",users.length);
	    const nodes = userList.querySelectorAll('[data-cowork="true"]');
	    if (users.length > 1)
	    {
		    nodes.forEach(node => {
		         const biChild = node.querySelector('.bi-people-fill');
			 // Si un enfant avec la classe "bi" est trouvé, applique opacity: 0
			 if (biChild) {
				 biChild.style.opacity = '0';
			 }
	    	    });
	    }

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
		console.log("userToDelete",userToDelete);
		const selectedElement = document.querySelector('.selected');
		const users = selectedElement.querySelectorAll('.user, .current-user');
		users.forEach(user => 
			{
				if (user.textContent == userToDelete)
				{
					user.closest('li').remove();
				}
		});
    
		const usersB = userList.querySelectorAll('.user, .current-user');
		users.forEach(user => {
			console.log(user);
	    	});

		console.log("nbres d'user inscrit",users.length);
		const nodes = userList.querySelectorAll('[data-cowork="true"]');
		if (usersB.length == 1)
		{
			nodes.forEach(node => {
		         const biChild = node.querySelector('.bi-people-fill');
			 // Si un enfant avec la classe "bi" est trouvé, applique opacity: 0
			 if (biChild) {
				 biChild.style.opacity = '1';
			 }
			});
		}
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



