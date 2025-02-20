
CTYPE html>
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
                <span id="texte">test</span>
                <button id="supprimer" onclick="supprimerTexte()">Supprimer</button>
                <button id="ajouter" style="display: none;" onclick="ajouterTexte()">Ajout</button>
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
        function supprimerTexte() {
            document.getElementById('texte').style.display = 'none';
            document.getElementById('supprimer').style.display = 'none';
            document.getElementById('ajouter').style.display = 'inline-block';
        }

        function ajouterTexte() {
            document.getElementById('texte').style.display = 'inline';
            document.getElementById('supprimer').style.display = 'inline-block';
            document.getElementById('ajouter').style.display = 'none';
        }
    </script>
</body>
</html>




