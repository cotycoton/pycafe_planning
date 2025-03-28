
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau 3x3</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        td {
            border: 1px solid black;
            text-align: center;
            padding: 10px;
            cursor: default;
        }
        td.selectable {
            cursor: pointer;
        }
        td.selectable:hover {
            background-color: #f0f0f0;
        }
        td.selected {
            background-color: #a0d2eb;
        }
        .user-list {
            text-align: left;
        }
        .user-list button {
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td>1,1</td>
            <td>1,2</td>
            <td>1,3</td>
        </tr>
        <tr>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
            <td class="selectable" onclick="selectOnly(this)">
                <div class="user-list">
                    <div>User1</div>
                    <div>User2 <button onclick="deleteUser(this, event)">Supprimer</button></div>
                </div>
            </td>
        </tr>
    </table>

    <script>
        function selectOnly(cell) {
            // Désélectionner toutes les cellules
            const allCells = document.querySelectorAll('.selectable');
            allCells.forEach(c => c.classList.remove('selected'));

            // Sélectionner la cellule cliquée
            cell.classList.add('selected');
        }

        function deleteUser(button, event) {
            // Empêcher la sélection de la cellule lors du clic sur le bouton
            event.stopPropagation();

            // Supprimer la ligne contenant le bouton cliqué
            const userRow = button.parentElement;
            userRow.remove();
        }
    </script>
</body>
</html>



