

<?php
// Vous pouvez préparer des variables PHP à transmettre
$title = "Popup avec Champ Texte";
$placeholder = "Entrez votre texte ici";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fenêtre Popup PHP + JS</title>
</head>
<body>
    <button id="openPopup">Ouvrir la Popup</button>

    <script>
        // Sélectionner le bouton
        const button = document.getElementById('openPopup');

        // Ajouter un écouteur d'événement pour ouvrir la popup
        button.addEventListener('click', function () {
            // Ouvrir une nouvelle fenêtre
            const popup = window.open('', '_blank', 'width=400,height=300');

            if (popup) {
                // Insérer du contenu HTML généré côté client dans la popup
                popup.document.write('
                    <html>
                    <head>
                        <title><?php echo $title; ?></title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                text-align: center;
                                padding: 20px;
                            }
                            input[type="text"] {
                                width: 80%;
                                padding: 8px;
                                margin-bottom: 20px;
                                border: 1px solid #ccc;
                                border-radius: 4px;
                            }
                            button {
                                padding: 10px 20px;
                                margin: 5px;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }
                            button.ok {
                                background-color: #28a745;
                                color: white;
                            }
                            button.cancel {
                                background-color: #dc3545;
                                color: white;
                            }
                        </style>
                    </head>
                    <body>
                        <h1><?php echo $title; ?></h1>
                        <input type="text" id="userInput" placeholder="<?php echo $placeholder; ?>">
                        <br>
                        <button class="ok" id="okButton">OK</button>
                        <button class="cancel" id="cancelButton">Annuler</button>

                        <script>
                            // Gérer le bouton OK
                            document.getElementById('okButton').addEventListener('click', function () {
                                const userInput = document.getElementById('userInput').value;
                                if (userInput) {
                                    alert("Vous avez saisi : " + userInput);
                                } else {
                                    alert("Le champ est vide !");
                                }
                                window.close(); // Fermer la popup
                            });

                            // Gérer le bouton Annuler
                            document.getElementById('cancelButton').addEventListener('click', function () {
                                alert("Vous avez annulé.");
                                window.close(); // Fermer la popup
                            });
                        </script>
                    </body>
                    </html>
                ');
            } else {
                alert("Popup bloquée par le navigateur.");
            }
        });
    </script>
</body>
</html>



