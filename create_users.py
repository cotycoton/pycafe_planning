

import csv
import mysql.connector
import bcrypt

def generate_hashed_password():
    password = "epicafe!"  # Vous pouvez générer un mot de passe aléatoire ici
    salt = bcrypt.gensalt()
    hashed = bcrypt.hashpw(password.encode(), salt)
    return hashed.decode()

def import_users_from_csv(csv_filename, db_config):
    connection = mysql.connector.connect(**db_config)
    cursor = connection.cursor()

    with open(csv_filename, newline='', encoding='utf-8') as csvfile:
        reader = csv.reader(csvfile,delimiter=";")
        next(reader)  # Ignorer l'en-tête

        for row in reader:
            firstname, lastname, email,phone,role = row
            password = generate_hashed_password()
            #phone = "0000000000"  # Valeur par défaut
            #role = "user"
            if (phone)=="":
                phone = "0000000000"  # Valeur par défaut
            #| id | firstname | lastname   | email                             | phone      | password                                                     | reset_token                                                                                          | active | created_at          | role  | remember_token
            #$stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, phone, password, active) VALUES (?, ?, ?, ?, ?, 0)");
            query = """
                INSERT INTO users (firstname, lastname, email, phone, password, active, role)
                VALUES (%s, %s, %s, %s, %s, 1, %s)
            """
            try:
                cursor.execute(query, (firstname, lastname, email, phone, password, role))
                connection.commit()
                if cursor.rowcount > 0:
                     print(f"Utilisateur {firstname} {lastname} inséré avec succès.")
                else:
                     print(f"Utilisateur {firstname} {lastname} pb")
            except mysql.connector.Error as err:
                print(f"Erreur MySQL lors de l'insertion de {firstname} {lastname}: {err}")

    connection.commit()
    cursor.close()
    connection.close()
    print("Importation terminée avec succès.")

# Configuration de la base de données
db_config = {
    'host': 'localhost',  # Modifier selon votre configuration
    'user': 'root',       # Modifier selon votre utilisateur MySQL
    'password': '', # Modifier avec votre mot de passe
    'database': 'EPICAFE_planning', # Modifier avec votre nom de base de données
    'raise_on_warnings': True
}

# Exécuter l'importation
import_users_from_csv('users.csv', db_config)

