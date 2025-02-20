

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fenêtre Modale avec Liste d'Utilisateurs</title>
    <style>
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
</head>
<body>
    <?php
    // Exemple de liste d'utilisateurs
    $users = ['Alice', 'Bob', 'Charlie', 'David', 'Eve'];
    ?>

    <!-- Bouton pour ouvrir la fenêtre modale -->
    <button id="openModalBtn">Sélectionner un utilisateur</button>

    <!-- Fenêtre modale -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Sélectionnez un utilisateur</div>
            <div>
                <select id="userSelect">
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user; ?>"><?php echo $user; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button id="cancelBtn">Annuler</button>
                <button id="okBtn">OK</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('userModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const okBtn = document.getElementById('okBtn');
        const userSelect = document.getElementById('userSelect');

        // Ouvrir la fenêtre modale
        openModalBtn.onclick = function () {
            modal.style.display = 'block';
        };

        // Fermer la modale sur Annuler
        cancelBtn.onclick = function () {
            modal.style.display = 'none';
            console.log(null); // Retourne null
        };

        // Retourner l'utilisateur sélectionné sur OK
        okBtn.onclick = function () {
            const selectedUser = userSelect.value;
            modal.style.display = 'none';
            console.log(selectedUser); // Retourne l'utilisateur sélectionné
        };

        // Fermer la modale si l'utilisateur clique en dehors
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>


