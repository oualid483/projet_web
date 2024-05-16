<!DOCTYPE html>
<html>
<head>
    <title>Interface Professeur</title>
    <style>
        body {
    background-color: #f0f0f0; /* Couleur de fond pastel */
    font-family: Arial, sans-serif;
    color: #333; /* Couleur de texte principale */
    text-align:center;
}

h2 {
    color: #6699cc; /* Couleur de titre */
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin-bottom: 5px;
}

input[type="text"],
input[type="submit"] {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc; /* Bordure pastel */
    background-color: #f8f8f8; /* Couleur de fond des champs */
}

input[type="submit"] {
    background-color: #66cccc; /* Couleur de fond du bouton */
    color: #fff; /* Couleur de texte du bouton */
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #4cbbb9; /* Couleur de fond du bouton au survol */
}

label {
    font-weight: bold;
}

.error {
    color: #cc3333; /* Couleur du texte d'erreur */
}

    </style>
</head>
<body>
    <?php
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ouabd";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les noms des étudiants avant de supprimer la table
        $students_before_update = [];
        $stmt_students = $conn->query("SELECT full_name FROM etudiant");
        while ($row = $stmt_students->fetch(PDO::FETCH_ASSOC)) {
            $students_before_update[] = $row['full_name'];
        }

        // Supprimer tous les étudiants avant de mettre à jour le code
        $stmt_delete_students = $conn->prepare("DELETE FROM etudiant");
        $stmt_delete_students->execute();

        // Mise à jour du code d'accès
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_code = $_POST['new_code'];

            // Supprimer l'ancien code d'accès
            $stmt_delete = $conn->prepare("DELETE FROM access_code");
            $stmt_delete->execute();

            // Insérer le nouveau code d'accès
            $stmt_insert = $conn->prepare("INSERT INTO access_code (code) VALUES (:code)");
            $stmt_insert->bindParam(':code', $new_code);
            $stmt_insert->execute();

            echo "Le code d'accès a été mis à jour avec succès.";
        }

        // Afficher les noms des étudiants avant la mise à jour du code
        echo "<h2>Étudiants avant la mise à jour du code :</h2>";
        echo "<ul>";
        foreach ($students_before_update as $student) {
            echo "<li>$student</li>";
        }
        echo "</ul>";

    } catch(PDOException $e) {
        echo "La requête a échoué : " . $e->getMessage();
    }
    ?>

    <h2>Mise à jour du code d'accès</h2>
    <form action="professor_interface.php" method="post">
        <label for="new_code">Nouveau code d'accès :</label><br>
        <input type="text" id="new_code" name="new_code"><br><br>
        <input type="submit" value="Mettre à jour">
    </form>
</body>
</html>