<?php

require 'database.php'; // Connexion à la base de données
require 'get_reservation.php';
require 'get_ouverture.php';
require 'get_function.php';

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

ini_set('session.cookie_secure', 1);       // HTTPS seulement
ini_set('session.cookie_httponly', 1);     // Inaccessible en JS
ini_set('session.cookie_samesite', 'Strict'); // Protection CSRF

session_start();

include 'calendar4.php';

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr'); // Définit la langue en français

//if (isset($_COOKIE['remember_me'])) {
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    // Rechercher l'utilisateur avec le token correspondant
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Token valide, démarrer une session
        $_SESSION['user_id'] = $user['id'];
    } else {
        // Token invalide, supprimer le cookie
        setcookie('remember_me', '', time() - 3600, '/');
    }
}



try {
    // Récupérer tous les utilisateurs sauf ceux avec le rôle 'admin'
    //$stmt = $pdo->prepare("SELECT id, firstname, lastname, email, phone FROM users WHERE role != 'admin'");
    $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, phone FROM users WHERE role !='superadmin' AND active = 1");
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

$maps = generateMapping($userData);
$mapping = $maps['mapping'];
$mapping_inv = $maps['mapping_inv'];
$admin=0;
if (isset($_SESSION['user_id'])) 
{
	$connected = 1;
	if (($_SESSION['user_role']=="admin") ||($_SESSION["user_role"]=="superadmin"))
	       $admin = 1;	
} else 
{
	$connected = 0;
}



// Récupérer le numéro de semaine depuis le paramètre URL

$date_now = new DateTime("NOW");

$week_default = $date_now->format('W');
$year_default = $date_now->format('o');

$weekNumber = isset($_GET['week']) ? (int)$_GET['week'] : $week_default;
$smax=552;
// Validation du numéro de semaine
if ($weekNumber < 1 || $weekNumber > $smax) {
    die("Numéro de semaine invalide. Veuillez fournir un numéro entre 1 et 52.");
}

$yearL= isset($_GET['year']) ? (int)$_GET['year'] : $year_default;

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
    if ($i == 0)
	    $first_day_week = $week_day;
    $day=$week_day->format('d-m-Y');
    $year=$week_day->format('Y');
    $daysOfWeek[] = $day;
    $week=$week_day->format('W');
    $year_week=$week_day->format('o');
    $month_week=$week_day->format('m');
    if ($year_week == $year+1)
    {
	//$month_week= 0;
    }
}

// Plages horaires
$timeSlots = [
    "", // Ligne vide pour l'affichage
    "8h10-10h30",
    "10h30-12h30",
    "", // Ligne vide pour l'affichage
    "15h30-17h30",
    "17h30-19h30",
    "19h30-22h00",
    "", // Ligne vide pour l'affichage
    "Evenements", // Ligne vide pour l'affichage
];

$jours_sem=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];

// Couleurs pour certaines cellules
$highlightedCells = [
    ["day" => 0, "times" => ["15h30-17h30", "17h30-19h30"]], // Lundi
    ["day" => 2, "times" => ["8h10-10h30", "10h30-12h30","15h30-17h30", "17h30-19h30"]],//$timeSlots], // Mercredi (toutes les plages horaires)
    ["day" => 4, "times" => ["15h30-17h30", "17h30-19h30"]], // Vendredi
    ["day" => 5, "times" => ["8h10-10h30", "10h30-12h30"]] // Samedi
];

// Précédent et suivant
$prev_date = clone($first_day_week);
$prev_date = $prev_date->modify('-7days');
$prev_Week = $prev_date->format('W');
$prev_Year = $prev_date->format('o');

$next_date = clone($first_day_week);
$next_date = $next_date->modify('+7days');
$next_Week = $next_date->format('W');
$next_Year = $next_date->format('o');


// Date actuelle
$currentDate = date('d-m-Y');
$curenteDateTime = new DateTime();
$curenteDateTime->setTime(0,0,0);
$actual_Week=date('W');
$actual_Year=date('o');

// Liste des utilisateurs
$users = ["user1", "user2", "user3", "user4", "user5", "user6", "user7", "user8", "user9", "user10"];
$users = ["user1", "user2", "user3", "user4", "user5", "user6", "user7", "user8", "user9", "user10","users11","users12","users13","users14"];
$users =array_keys($mapping_inv);//nameAbbreviations;
sort($users);
$users_all = $users;

$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];


$currentUser = $mapping[$_SESSION['user_id']];
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
		font-size:1.8rem;
		display:flex;
 		align-items: center;
		justify-content:center;
        } 
        table {
            border-collapse: collapse;
            width: 100%;
	    margin: 5px auto;
	    //table-layout: fixed;
        }
        th, td {
            border: 1px solid black;
            //padding: 8px;
            padding: 2px;
	    text-align: center;
	    width: 12vw;
	}
	tr
	{
		height:30px;
	}
	.tr_inter
	{
		height:10px;
	}   

	.calendrier tr
	{
		height:15px;
	}
        .calendrier {
            width: 100px;
            table-layout: fixed;
            border-collapse: collapse;
	    font-size:0.8rem;
        }
        
        .calendrier td, .calendrier th {
            width: 28px; /* 100% / 7 colonnes */
            height: 5px; /* Hauteur fixe des cellules */
	    text-align: center;
	    font-size:0.8rem;
            border: 1px solid black;
        }
        .calendrier table {
            border-collapse: collapse;
            width: 100%;
	    margin: 0px auto;
        }

	.col_1st{
	    width: 5.5vw;
	}
	.col_jour{
	    width: 13.5vw;
	}
	.ligne_event{
	    background:#eaf7ee;
	}
	.delete
	{
	    padding:5px;
	    text-decoration: none;
	    font-size: 14px;
	    font-weight:normal;
	}
	.deleteL
	{
	    padding:5px;
	    padding-left:20px;
	    text-decoration: none;
	    font-size: 14px;
	    font-weight:normal;
	}	
        .cowork {
	}

        th {
            background-color: #f0f0f0;
        }
        .highlight {
            //background-color: #dcedc8;
            background-color: #d6eaf8;
	    cursor: pointer;
            padding:1px;
	}
	.epidej{
            background-color: #f39c12 !important;

	}
	.person
	{
		content:attr(value);
		font-size:12px;
		color: #000000;
		background: white;
		border-radius:50%;
		border-color:black;
		padding: 0 5px;
		position:relative;
		left:-8px;
		top:-10px;
		height:20px;
		border: 1px solid grey;
		opacity:0.9;
	}
	.square {
		display: flex;
        	width: 15px;
		height: 15px;
		#margin-left:5px;
		margin-right:5px;
		margin-top:2px;
		float:right;
		color:black !important;
	}
	.red
	{
		background:red;
	}
	.yellow
	{
		background:yellow;
	}
	.blue
	{
		background:blue;
	}
	.green
	{
		background:green;
	}
        .not_open {
            background-color: #ffffff;
            cursor: pointer;
            padding:1px;
        }
        .selected {
            background-color: #aed6f1 !important;
        }
        .nav-arrows {
            display: flex;
            justify-content: space-between;
	    align-items: center;
            margin: 10px;
	    margin-top:2px;
	    width:100%;   
        }
        .nav-arrows a {
            text-decoration: none;
            font-size: 24px;
            color: #000;
            margin-left: 20px;
            margin-right: 20px;
        }
        .today {
            background-color: #4caf50 !important;
            color: white;
	}
	.menu
	{
            display: flex;
            justify-content: center;
	    align-items: center;
	}
        .user-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
	}
        .event-list {
            margin: 0;
            padding: 0;
            list-style-type: none;
	}
	.event
	{
		margin:2px;
		padding:0px;
	}
	#col0 {
	    font-size: 0.8rem;
	}
	.col_jour {
	    font-size: 0.8rem;
	}
        .user-list li {
	    //padding: 2px 0;
	    padding: 0px 0;
	    font-size: 0.8rem;
        }
        .action-button {
            #margin-left: 5px;
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
        .current-user, .user {
	    padding: 2px 2px;
	    margin-left:2px;
	    margin-right:2px;
            border-radius: 4px;
	}
	.current-user
	{
            color: #007bff;
            background-color: #fff59d;
            font-weight: bold;
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
            z-index: 1051;
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
            width: 550px;
	}
	.modal-content-help {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 750px;
        }

        .modal-header { font-weight: bold; margin-bottom: 15px; display:block;}
        .modal-footer { margin-top: 15px; text-align: right; }
	.modal-footer button { margin-left: 10px; }

	#eventChoiceContainer
	{
		margin-top:10px;
	}	

    </style>
    <link rel="stylesheet" href="event.css"> <!-- Import du CSS personnalisé -->
    <link rel="stylesheet" href="help.css"> <!-- Import du CSS personnalisé -->
    <link rel="stylesheet" href="calendar4.css"> <!-- Import du CSS personnalisé -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="db_planning_add.js"></script> <!-- Import du JS -->
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

	function getCookie(name) {
		const value = `; ${document.cookie}`;
		const parts = value.split(`; ${name}=`);
		if (parts.length === 2) return parts.pop().split(';').shift();
	}


	/**
	 * Vérifie si l'utilisateur est connecté en interrogeant le serveur.
	 * @returns {Promise<boolean>} - Une promesse qui résout à `true` si l'utilisateur est connecté, sinon `false`.
	 */
	async function checkIfUserIsLoggedIn() {
		try {
			// Envoyer une requête au serveur pour vérifier l'état de connexion
			const response = await fetch('check-auth.php', {
			method: 'GET',
				credentials: 'include' // Inclure les cookies de session (si utilisés)
		});

			// Vérifier si la réponse est OK
			if (!response.ok) {
				throw new Error("Erreur lors de la vérification de l'état de connexion.");
			}

			// Convertir la réponse en JSON
			const data = await response.json();

			// Retourner true si l'utilisateur est connecté, sinon false
			return data.isLoggedIn === true;
		} catch (error) {
			console.error("Erreur :", error);
			return false; // En cas d'erreur, retourner false
		}
	}


	function isUserLoggedIn() {


		if (checkIfUserIsLoggedIn())
			return true;

		// Obtenir tous les cookies sous forme de chaîne
		const cookies = document.cookie;

		// Vérifier si le cookie 'remember_me' est présent
		///const cookies = document.cookie.split(';').map(cookie => cookie.trim());
		console.log("cookies",cookies);
		r = cookies.split(';').some(cookie => cookie.trim().startsWith('remember_me='));
		if (r)
			return true;

		const sessionId = getCookie('session_id');
		if (sessionId) {
			console.log('Utilisateur connecté');
			return true;
		} else {
			console.log('Utilisateur non connecté');
		}

		const token = localStorage.getItem('authToken'); // Récupérer le token
		if (token) {
			// Vérifier si le token est valide (vous pouvez décoder le JWT ou faire une requête au serveur)
			console.log("utilisateur connecte (authToken)");
			return true; // L'utilisateur est connecté
		}
		console.log("utilisateur non connecte");
		return false; // L'utilisateur n'est pas connecté
	}


	//creation bouton ajouter
	function addButtonUser(cell)
	{

		if (! isUserLoggedIn())
			return;
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
		let users_all = <?php echo json_encode($users_all); ?>;
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
			addButton.innerHTML = '<div>Ajouter&nbsp;&nbsp;&nbsp<i class="bi bi-clipboard-plus"></i></div>';
			addButton.className = 'action-button add-user';
			addButton.addEventListener('click', () => 
			{
				console.log('click current-user ajouter');
			
				td = addButton.closest('td');
				date_td = td.id;
				console.log("closest td",date_td);
				eventChoice = document.getElementById("eventChoiceContainer");
				eventChoice.style.display='none';

				eventSelect = document.getElementById("eventSelect");
				while (eventSelect.options.length > 0)
				{
					eventSelect.remove(0);
				}

				getEventsByDate(date_td).then(
					data => 
				{
						if (data) {
							if (data.success)
							{
								console.log("Réservations :", data);
								eventChoice = document.getElementById("eventChoiceContainer");
								eventChoice.style.display='block';
								events = data.events;
							
								
								eventSelect = document.getElementById("eventSelect");
								const opt1 = document.createElement("option");
								opt1.text= "Aucun";
								opt1.dataset.id = -1;
								eventSelect.add(opt1,null);
								for (let e = 0; e < events.length;e++)
								{
									console.log("event",e,events[e]);
									const opt1 = document.createElement("option");
									opt1.text= events[e].nom;
									opt1.dataset.id = events[e].id;
									eventSelect.add(opt1,null);

								}
							}
						} else {
							console.log("Aucune réservation trouvée ou erreur.");
						}
					}	
				);


				// necessite etre connecter
				if (connected == "1") 
				{
					console.log("connectec = 1");

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
			liItem.classList.add('li');
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
			cell = event.target;
			tr = cell.closest("tr");
			td = cell.closest("td");
			plage_resa = tr.id;
			date_resa = td.id;
			ouverture = 1
	        	if (event.target.classList.contains('not_open')) 
			{
                            	cell.classList.remove("not_open");
				cell.classList.add("highlight");
				ouverture = 1;
                        }
			else if (event.target.classList.contains('highlight')) 
			{
				cell.classList.remove("highlight");
				cell.classList.add("not_open");
				ouverture = 0;
			}
			else if (event.target.classList.contains('user')) 
			{
				const parent = cell.closest('.user-list');
				const parent2 = parent.parentNode;
				if (parent2.classList.contains('not_open'))
				{
					parent2.classList.remove("not_open");
					parent2.classList.add("highlight");
					ouverture = 1;
				}
				else
				{
					parent2.classList.remove("highlight");
					parent2.classList.add("not_open");
					ouverture = 0;
				}
			}
			console.log('Cellule cliquée :', event.target.textContent,tr.id,td.id,ouverture);
			enregistrerEtat(date_resa,plage_resa,ouverture);


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
			 else if ( (event.target.classList.contains('user')) || (event.target.classList.contains('current-user')) || (event.target.classList.contains('li'))) 
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
			 else if ( (event.target.classList.contains('user-list')) ) 
			 {
				cell = event.target;
				const parent2 = cell.parentNode;

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
    <div>
    <div class="nav-arrows">
        <a href="?week=<?php echo $prev_Week; ?>&year=<?php echo $prev_Year; ?>" title="Semaine précédente">&#8592;</a>
        <h2>Tableau de la Semaine <?php echo $week; ?></h2>
<?php
	generateCalendar($month_week, $year_week, $week);
?>
        <a href="?week=<?php echo $next_Week; ?>&year=<?php echo $next_Year; ?>" title="Semaine suivante">&#8594;</a>
    </div>
    </div>

<?php
if (isset($_SESSION['user_id'])) {
    // L'utilisateur est connecté
    //echo "<h1>Bienvenue " . htmlspecialchars($_SESSION['user_name']) . " sur le planning de l'epicafe!</h1>";
} else {
}
?>

    <table id="tableContainer">
        <thead>
            <tr>
                <th class="col_1st">#</th>
		<?php
		$list_days=array();
                foreach ($daysOfWeek as $index => $day) {
                    $dtime = DateTime::createFromFormat('d-m-Y', $day);
		    $class = ($day === $currentDate) ? "today" : "";
		    $day_m = DateTime::createFromFormat("d-m-Y", $day);
		    $day_f = strftime("%d %B %Y", $day_m->getTimestamp());
		    echo "<th class='$class col_jour' data-param=\"" . $day_m->format("d-m-Y") . "\">" . $jours[$dtime->format('w')] . "<br>" . $day_f . "</th>";
		    $list_days[]=$day;
                }
                ?>
            </tr>
        </thead>
        <tbody>
        <?php
		foreach ($timeSlots as $index => $timeSlot) 
		{
			$plage_resa="$timeSlot";
			if ($timeSlot=="")
				echo "<tr class=\"tr_inter\">";
			else
			{
				if ($timeSlot=="Evenements")
					echo "<tr id=\"$timeSlot\" class=\"ligne_event\">";
				else
					echo "<tr id=\"$timeSlot\">";
			}
			echo "<td id=\"col0\">" . $timeSlot . "</td>";
			for ($col = 0; $col < 7; $col++) 
			{
				$jour = $jours_sem[$col];
				$cellClass = "not_open";
				$date_resa="$list_days[$col]";
				$date_ouv = getOuverture($date_resa,$plage_resa);
				$isEpidej = isFirstSaturdayOfMonth($date_resa);
				//echo $date_ouv;
				foreach ($highlightedCells as $highlight) 
				{
					if ($highlight["day"] == $col && in_array($timeSlot, $highlight["times"])) 
					{
						$cellClass = "highlight";
						$epidejClass="";
						if ($isEpidej == 1)
							$epidejClass=" epidej";
						break;
					}
					else
					{
						$epidejClass="";
					}
				}
				if ($date_ouv != null)
				{
					if ($date_ouv == 1)
					{
						$cellClass = "highlight";
					}
					else
					{
						$cellClass = "not_open";
						$epidejClass="";
					}
				}
				if ($timeSlot === "") 
				{
					echo "<td class=\"col_jour\"></td>"; // Cellules vides pour les lignes sans plages horaires
				}
				else if ($timeSlot === "Evenements") 
				{
					$events = getEventsByDate($date_resa,$pdo_event);
					echo "<td class=\"col_jour ligne_event\" data-param=\"" . $date_resa . "\">";
					echo "<ul class=\"event-list\">";
					if ($isEpidej==1)
					{
						echo "<li class=\"li\"><div class=\"event\">";
						echo "Epidej<div class=\"square epidej\"></div>";
						echo "</div></li>";
					}
					if (!empty($events))
					{
						foreach ($events as $event)
						{
							$color=$event["color"];
							$person=$event["personnes"];
							if ($person == "0")
								$person = '';
							echo "<a href=\"#\" onclick=\"editEvent(this," . $event['id'] . ")\"  id=\"href_event_" . $event['id'] . "\"><li class=\"li\"><div class=\"event\" data-param=\"" . $event["id"] . "\">";
							echo $event['nom'] . "<br>" . $event["heure_debut"] . " - " . $event["heure_fin"] . "<div class=\"square $color\">";
							if ($person != '')
								echo "<div class=\"person\">$person</div>";
							echo "</div></div></li>";
						}
					}
					echo "</ul></td>"; // Cellules vides pour les lignes sans plages horaires

				}
				else 
				{
					// Sélectionner 3 utilisateurs au hasard
					$reservations = getReservations($date_resa, $plage_resa, $pdo_planning);
					$users_resa=[];
					$users_resa_id=[];
					$cowork_resa=[];
					$events_resa=[];
					if (!empty($reservations)) 
					{
						foreach ($reservations as $res) 
						{
							$users_resa_id[]=$res['id'];
							$users_resa[]=$mapping[$res['id']];
							$cowork_resa[]=$res['cowork'];
							$events_resa[]=$res['evenements'];
						}
						$cellClass = "highlight";
					}
					//$randomUsers = array_slice($users, rand(0, count($users) - 3), 3);
					//$randomUsers = array_slice($users, rand(0, count($users) - 3), rand(0,count($users)));
					//echo "<td id=\"$jour-$list_days[$col]\" class='$cellClass col_jour'>";
					echo "<td id=\"$date_resa\" class='$cellClass$epidejClass col_jour'>";
					if ($cellClass === "highlight") 
					{
						echo "<ul class='user-list'>";
						//foreach ($randomUsers as $user)
						$c = 0;
						$opacity = 0;
						foreach ($users_resa as $user) 
						{
							$opacity = $opacity + $cowork_resa[$c];
						}
						if ($opacity > 1)
							$opacity = 0;
						else
							$opacity = 1;
						$c = 0;
						foreach ($users_resa as $user) 
						{
							$isAdmin = $admin;
							$cowork = (bool) $cowork_resa[$c];
							$cowork_html = "";
							$user_id=$users_resa_id[$c];
							$event_id=$events_resa[$c];
							$event = getEventsById($event_id,$pdo_event);
							$event_color = '';
							if ($event != NULL)
							{
								$event_color= $event['color'];
							}
							if ($cowork ==1)
							{
								$cowork = "true";
								$cowork_html = "<i class=\"bi bi-people-fill\" style=\"opacity: $opacity; padding-right: 5px;\"></i>";
							}
							else
								$cowork = "false";
							if ($user == $currentUser) 
							{
								echo "<li class=\"li\"><div data-cowork=\"$cowork\" data-user_id=\"$user_id\" class=\"current-user\">$cowork_html$user";
								if ($event_color != '')
									echo "<div class=\"square $event_color\"></div>";
								echo "<button onclick=\"removeItemAdmin(this,'" .$user . "'," .$user_id . ")\" class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
								echo "</div>";
							}
							elseif ($isAdmin) 
							{
								echo "<li  class=\"li\"><div  data-cowork=\"$cowork\" data-user_id=\"$user_id\"  class=\"user\">$cowork_html$user";
								if ($event_color != '')
									echo "<div class=\"square $event_color\"></div>";
								echo "<button onclick=\"removeItemAdmin(this,'" .$user . "', " .$user_id . ")\"  class='action-button remove-user hidden-button'><i class='bi bi-trash'></i></button>";
								echo "</div>";
							}
							else
							{
								echo "<li class=\"li\"><div  data-cowork=\"$cowork\" data-user_id=\"$user_id\"  class=\"user\">$cowork_html$user";
								if ($event_color != '')
									echo "<div class=\"square $event_color\"></div>";
								echo "</div>";
							}
							echo "</li>";
							$c=$c+1;
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
	echo "<div class=\"menu\">";
	if (($week != $actual_Week) || ($yearL != $actual_Year))
		echo "<a href=\"planning.php?week=$actual_Week&year=$actual_Year\">Semaine actuelle</a>";
	if (($_SESSION['user_role']=="admin") ||($_SESSION["user_role"]=="superadmin"))
	{
		//echo "- <a href=\"#\" onclick=\"cleanSelected()\" id=\"toggleEditMode\" >Edit mode</a> - ";
		echo "<button class=\"btn btn-light\" onclick=\"cleanSelected()\" id=\"toggleEditMode\" >Activer Edition créneau</button>";
    		echo "<button class=\"btn btn-light\" onclick=\"openModal()\">Créer un événement</button></p>";
	}
	echo "</div>";
	echo "<p align=\"center\"><i>Connecté en tant que " . htmlspecialchars($_SESSION['user_name']);
	if (($_SESSION['user_role']=="admin") ||($_SESSION["user_role"]=="superadmin"))
		echo " (admin) " ;
        //echo "<div class=\"container mt-5\">";
        //echo "<button class=\"btn btn-light\" data-toggle=\"modal\" data-target=\"#eventModal\">Voir les événements</button>";
        //echo "</div>";
	echo " -<button class=\"btn btn-light\" id=\"openSEModalBtn\" onclick=\"openModalShowEvents()\" >Liste des Evenements</button>";
	echo "-<button class=\"btn btn-light\" id=\"openModalBtn\" onclick=\"openModalHelp()\" >Afficher la documentation</button>";
	echo "- <a href=\"logout.php\">Déconnexion</a>";
	echo "</i></p>";
} else {
	echo "<p align=\"center\">Accès en lecture seul, veuillez vous <a href=\"login2.php\">connecter</a>";
    echo "<button class=\"btn btn-light\" id=\"openSEModalBtn\" onclick=\"openModalShowEvents()\" >Liste des Evenements</button></p>";

}
?>

	<script>
        // Récupération du lien par son ID
	var link = document.getElementById('toggleEditMode');

	if (link)
	{

		// Ajout d'un écouteur d'événement pour le clic sur le lien
		link.addEventListener
		(	'click', function(event) 
			{
				// Empêcher le comportement par défaut du lien
				event.preventDefault();

				// Toggle la variable entre 0 et 1
				editMode = editMode === 0 ? 1 : 0;

				// Changer le texte du lien en fonction de la valeur de la variable
				if (editMode === 1) {
					link.textContent = 'Désactiver Edit créneau';
				} else {
					link.textContent = 'Activer Edition créneau';
				}

				// Afficher la valeur de la variable dans la console (pour le débogage)
				console.log('editMode:', editMode);
			}
		);
	}



    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fenêtre modale -->
    <div id="userModal" class="modal">
	<div class="modal-content">
            <?php
		if (($_SESSION['user_role']=="admin") ||($_SESSION["user_role"]=="superadmin"))
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
	     <div class="mb-3" id="eventChoiceContainer">
                       <label for="eventChoiceLabel" class="form-label">S'associer à un evenement</label>
                       <div class="event-select-container">
                           <!--div id="indi" class="color-indicator"></div--> <!-- Cercle affiché -->
                           <select class="form-select color-dropdown" id="eventSelect">
                           </select>
		       </div>
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
    
	<!-- Conteneur où la modale sera chargée -->
    <div id="modalContainer">
    </div>
    <div id="modalHelp">
            <?php include 'help.php'; ?>
    </div>
    <div id="modalEventsShow">
            <?php include 'event_modal.php'; ?>
    </div>

    <!--?php include "event.php" ?-->

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
	
	function formatDate(dateStr) {
		const mois = [
			"Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
			"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
		];

		const [jour, moisNum, annee] = dateStr.split("-");

		return `${parseInt(jour)} ${mois[parseInt(moisNum) - 1]} ${annee}`;
	}


	function removeItemAdmin(button,user,user_id) {

		tr = button.closest("tr");
		td = button.closest("td");
		plage_resa = tr.id;
		date_resa = formatDate(td.id);
		console.log("Validation de la suppresion " + user_id);
		modalDelete.style.display = 'inline';
		header = modalDelete.querySelector(".modal-header");
		//header.textContent = "Supprimer " + user + "\njour : " + date_resa + "\nplage :" + plage_resa ;
		//header.innerHTML = "<p>Supprimer " + user + "</p><p>jour : " + date_resa + "</p><p>plage :" + plage_resa +"</p>" ;
		//header.innerHTML = "<li>Supprimer " + user + "</li><li>jour : " + date_resa + "</li><li>plage :" + plage_resa +"</li>" ;
		header.innerHTML = "<div class=\"delete\">Supprimer le creneau ? </div><div class=\"deleteL\">" + user + "</div><div class=\"deleteL\">Jour : " + date_resa + "</div><div class=\"deleteL\">Plage horaire:" + plage_resa +"</div>" ;

		modalDelete.dataset.param = user_id;
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

	    tr = userList.closest("tr");
	    td = userList.closest("td");
	    console.log("saving db ",selectedUser,tr.id,td.id);
	    const mapping = <?php echo json_encode($mapping, JSON_HEX_TAG); ?>;
	    const mapping_inv = <?php echo json_encode($mapping_inv, JSON_HEX_TAG); ?>;
	    plage_resa = tr.id;
	    date_resa = td.id;
	    detail_resa = "";
	    cowork_resa = serviceUser;
	    nom_resa = "";
	    prenom_resa = "";
	    console.log("mapping_inv",mapping_inv);
	    id_resa = mapping_inv[selectedUser];
	    eventSelect = document.getElementById("eventSelect");



	    // Vérifier si une option est sélectionnée
	    let dataId = -1;
	    let colorEvent = '';
	    if (eventSelect.options.length > 0 && eventSelect.selectedIndex !== -1) {
		    const selectedOption = eventSelect.options[eventSelect.selectedIndex]; // Récupérer l'option sélectionnée
		    const value = selectedOption.value; // Récupérer la valeur
		    dataId = selectedOption.getAttribute("data-id"); // Récupérer l'attribut data-param
		    
		    console.log("Valeur sélectionnée :", value);
		    console.log("Data-param :", dataId);
	    } else {
		    console.log("Aucune option disponible ou sélectionnée.");
	    }


	    console.log("save db id",id_resa);
	    ajouterReservation(date_resa, plage_resa, id_resa, nom_resa, prenom_resa, cowork_resa, detail_resa,dataId);
	    console.log("save ok");

	    userDiv.textContent = userToAdd;
	    if (serviceUser)
	    {
		    let icon = document.createElement("i");
		    icon.className = "bi bi-people-fill"; // Ajouter les classes Bootstrap Icons
		    icon.style.opacity = "1.0"; // Appliquer la transparence
		    icon.style.paddingRight = "5px";  

		    userDiv.prepend(icon);
	    }
	    const removeButton = document.createElement('button');
	    removeButton.innerHTML = '<i class="bi bi-trash"></i>';
	    removeButton.className = 'action-button remove-user e';
						
	    console.log("removeItemAdmin(",removeButton,userToAdd);
	    //removeButton.addEventListener('click', () => modalDelete.style.display = 'inline');

	    const contentHtml = "<button onclick=\"removeItemAdmin(this,'" + userToAdd + "'," + id_resa +")\" class=\"action-button remove-user hidden-button\"><i class=\"bi bi-trash\"></i></button>";
	    //	userDiv.innerHTML += contentHtml;
	    div_square = document.createElement("div");
	    if (dataId != -1)
	    {
//		await asyncGetEventsById(date_td).then(
//					data => 
//	    				{
//						colorEvent = data.color;
//					}
//					)
//				);
//
	    	getEventById(dataId).then(
			    event => 
		    		{
					if (event) {
						colorEvent = event.color;
						userDiv.appendChild(div_square);
	    					div_square.className = "square " + colorEvent;
						console.log("colorEvent",colorEvent);
					}
		    		}
	    	);
	    }

	    userDiv.appendChild(removeButton);
	    removeButton.setAttribute('onclick', "removeItemAdmin(this, '"+userToAdd+ "'," + id_resa + ")");
	    //removeButton.addEventListener('click', () => removeItemAdmin(removeButton,userToAdd));

	    userDiv.parentNode.parentNode.parentNode.classList.add('cowork');

	    users = userList.querySelectorAll('.user, .current-user');
	    users.forEach
            (
		    user => 
	    	    {
				console.log(user);
	    	    }
	    );

	    console.log("nbres d'user inscrit",users.length);
	    const nodes = userList.querySelectorAll('[data-cowork="true"]');
	    if (users.length > 1)
	    {
		    nodes.forEach
	            (
			    node => 
	                    {
				    const biChild = node.querySelector('.bi-people-fill');
				    // Si un enfant avec la classe "bi" est trouvé, applique opacity: 0
				    if (biChild) 
				    {
					    biChild.style.opacity = '0';
				    }
	    		    }
		    );
	    }

	    const parentLi = addButton.closest('li');
	    console.log(parentLi);
	    console.log(parentLi.textContent);
	    parentLi.remove();
        };
	

        // Fermer la modale sur Annuler
	cancelBtnDelete.onclick = function () 
	{
            modalDelete.style.display = 'none';
            console.log(null); // Retourne null
        };

        // Retourner l'utilisateur sélectionné sur OK
	okBtnDelete.onclick = function () 
	{

		modalDelete.style.display = 'none';

		const userToDelete = modalDelete.dataset.param
		console.log("userToDelete",userToDelete);
		
		const selectedElement = document.querySelector('.selected');
		
		tr = selectedElement.closest("tr");
		td = selectedElement.closest("td");
		console.log("REMOVE ",userToDelete,tr.id,td.id);
		plage_resa = tr.id;
		date_resa = td.id;
		id_resa = userToDelete;
		deleteReservation(date_resa,plage_resa,id_resa);


		const users = selectedElement.querySelectorAll('.user, .current-user');
		users.forEach
		(
			user => 
			{
				if (user.textContent == userToDelete)
				{
					user.closest('li').remove();
				}
			}
		);
    
		const usersB = userList.querySelectorAll('.user, .current-user');
		users.forEach
		(
			user => 
			{
				console.log(user);
	    		}
		);

		console.log("nbres d'user inscrit",users.length);
		const nodes = userList.querySelectorAll('[data-cowork="true"]');
		if (usersB.length == 1)
		{
			nodes.forEach
			(
				node => 
				{
					const biChild = node.querySelector('.bi-people-fill');
					// Si un enfant avec la classe "bi" est trouvé, applique opacity: 0
					if (biChild) {
						biChild.style.opacity = '1';
					}
				}
			);
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
    <!--script src="event.js" defer></script--> <!-- Import du JS -->
    <script src="event.js"></script> <!-- Import du JS -->
    <script src="help.js"></script> <!-- Import du JS -->
    <script src="events_modal.js"></script> <!-- Import du JS -->

</body>
</html>



