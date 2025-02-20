
<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'votre_base';
$user = 'votre_utilisateur';
$password = 'votre_mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération du contenu de la cellule centrale
    $stmt = $pdo->prepare("SELECT contenu FROM tableau_etat WHERE cellule = :cellule");
    $stmt->execute(['cellule' => 'cellule-centrale']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si un contenu existe, l'utiliser, sinon définir une valeur par défaut
    $contenu = $result ? $result['contenu'] : 'test';
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau 3x3 Interactif</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 20px auto;
        }
        td {
            border: 1px solid #000;
            width: 100px;
            height: 50px;
            text-align: center;
            vertical-align: middle;
        }
        button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td id="cellule-centrale">
                <span id="texte" style="display: <?= empty($contenu) ? 'none' : 'inline'; ?>;"><?= htmlspecialchars($contenu) ?></span>
                <button id="supprimer" style="display: <?= empty($contenu) ? 'none' : 'inline-block'; ?>;" onclick="supprimerTexte()">Supprimer</button>
                <button id="ajouter" style="display: <?= empty($contenu) ? 'inline-block' : 'none'; ?>;" onclick="ajouterTexte()">Ajout</button>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

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

        function supprimerTexte() {
            document.getElementById('texte').style.display = 'none';
            document.getElementById('supprimer').style.display = 'none';
            document.getElementById('ajouter').style.display = 'inline-block';
            sauvegarderEtat('');
        }

        function ajouterTexte() {
            document.getElementById('texte').style.display = 'inline';
            document.getElementById('supprimer').style.display = 'inline-block';
            document.getElementById('ajouter').style.display = 'none';
            sauvegarderEtat('test');
        }
    </script>
</body>
</html>


