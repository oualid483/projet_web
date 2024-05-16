<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Étudiant</title>
    <style>
        body {
    background-color: #f0f0f0; /* Couleur de fond pastel */
    font-family: Arial, sans-serif;
    color: #333; /* Couleur de texte principale */
    text-align: center;
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
    <h2>Liste des Étudiants</h2>
    <ul>
        <?php
        session_start();

        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ouabd";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Récupérer tous les étudiants depuis la base de données
            $stmt_students = $conn->query("SELECT full_name FROM etudiant");
            $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($students as $student) {
                echo "<li>" . $student['full_name'] . "</li>";
            }
        } catch(PDOException $e) {
            echo "La requête a échoué : " . $e->getMessage();
        }
        ?>
    </ul>

    <h2>Saisie de présence</h2>
    <form action="student_interface.php" method="post" onsubmit="return validateForm()">
        <label for="full_name">Nom complet :</label><br>
        <input type="text" id="full_name" name="full_name" required><br><br>

        <label for="access_code">Code d'accès :</label><br>
        <input type="text" id="access_code" name="access_code" required><br><br>

        <input type="submit" value="Soumettre">
    </form>

    <?php
    // Vérifier si le formulaire de saisie de présence a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $full_name = $_POST['full_name'];
        $access_code = $_POST['access_code'];

        try {
            // Connexion à la base de données
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifier si le code d'accès est valide
            $stmt_access_code = $conn->prepare("SELECT * FROM access_code WHERE code = :code");
            $stmt_access_code->bindParam(':code', $access_code);
            $stmt_access_code->execute();
            $row_access_code = $stmt_access_code->fetch(PDO::FETCH_ASSOC);

            if (!$row_access_code) {
                echo "Code d'accès invalide.";
            } else {
                // Insérer la présence de l'étudiant dans la base de données
                $stmt_insert_student = $conn->prepare("INSERT INTO etudiant (full_name) VALUES (:full_name)");
                $stmt_insert_student->bindParam(':full_name', $full_name);
                $stmt_insert_student->execute();

                echo "Votre présence a été enregistrée avec succès.";
            }
        } catch(PDOException $e) {
            echo "La requête a échoué : " . $e->getMessage();
        }
    }
    ?>

<script>
    function validateForm() {
        var fullName = document.getElementById("full_name").value;
        var accessCode = document.getElementById("access_code").value;

        if (fullName == "" || accessCode == "") {
            alert("Veuillez remplir tous les champs.");
            return false; // Empêche l'envoi du formulaire
        }
    }
</script>

</body>
</html>