<?php


// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$date_now = new DateTime("NOW");
$week_no= $date_now->format("W");
if(isset($_GET['week_no'])) 
{
	$week_no = $_GET['week_no'];
}
include 'week.php';
$calendar = new CalendarWeek('2024-05-12',$week_no);
$calendar->add_event('Birthday', '2024-05-03', 1, 'green');
$calendar->add_event('Doctors', '2024-05-04', 1, 'red');
#$calendar->add_event('Holiday', '2024-05-16', 7);
$week_no_plus=$week_no+1;
$week_no_moins=$week_no-1;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Event Calendar</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link href="style.css" rel="stylesheet" type="text/css">
		<link href="week.css" rel="stylesheet" type="text/css">

	</head>
	<body>
	    <nav class="navtop">
	    	<div>
    			<h1 class="my-5" align="center">Salut <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> Bienvenue sur le planning de l'epicafe</h1>
	    	</div>
	    </nav>
		<div id="flex-container">
		    <div class="left">
			<?php echo '<a href="' . 'https://www.telegraphe-optifluides.fr/epicafe/planning/ex.php?week_no=' . $week_no_moins. '"><img src="arrow-left.png"></a>' ?>
		    </div>
		    <div class="table_div">
			<?=$calendar?>
		    </div>
		    <div class="right">
			<?php echo '<a href="' . 'https://www.telegraphe-optifluides.fr/epicafe/planning/ex.php?week_no=' . $week_no_plus. '"><img src="arrow-right.png"></a>' ?>
		   </div>
		</div>

    <p align="center">
        <a href="reset-password.php" class="btn btn-warning">Reinitialiser le mot de passe</a>
        <a href="logout.php" class="btn btn-danger ml-3">Quitter la session</a>
    </p>

    <script>
        function sauvegarderEtat(contenu) {
            const data = new FormData();
            data.append('cellule', 'cellule-centrale');
            data.append('contenu', contenu);

            fetch('sauvegarde.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.text())
            .then(result => {
                console.log('Sauvegarde réussie:', result);
            })
            .catch(error => {
                console.error('Erreur lors de la sauvegarde:', error);
            });
        }

        function supprimerTexte(cellule) {
            console.log('supprimerTexte -> texte-'+cellule);
            document.getElementById('texte-'+cellule).style.display = 'none';
            document.getElementById('ajouter-'+cellule).style.display = 'table';
            sauvegarderEtat('');
        }

        function ajouterTexte(cellule) {
            console.log('ajoutTexte -> texte-'+cellule);
            document.getElementById('texte-'+cellule).style.display = 'inline';
            document.getElementById('ajouter-'+cellule).style.display = 'none';
            sauvegarderEtat('test');
        }

	function toggleSelection(cell) {
		cell.classList.toggle('selected');
	}
}


    </script>

	</body>
</html>
