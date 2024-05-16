<!DOCTYPE html>
<html>
<head>
    <title>Interface Professeur</title>
    <style>
        body {
            background-color: #f0f0f0; 
            font-family: Arial, sans-serif;
            color: #333; 
            text-align:center;
        }

        h2 {
        color: #6699cc; 
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
        border: 1px solid #ccc; 
        background-color: #f8f8f8; 
        }

        input[type="submit"] {
        background-color: #66cccc; 
        color: #fff; 
        cursor: pointer;
        }

    input[type="submit"]:hover {
        background-color: #4cbbb9; 
    }

    label {
        font-weight: bold;
    }

    .error {
        color: #cc3333; 
    }

    .student-list {
        margin-top: 20px;
        text-align: center;
    }

    .student-list table {
        margin: 0 auto;
    }

    .student-list th {
        background-color: #6699cc;
        color: #fff;
        padding: 10px;
    }

    .student-list td {
        padding: 5px;
    }
</style>
</head>
<body>
    <h2>Selectionner une date pour afficher la liste des etudiants</h2>
    <form action="professor_interface.php" method="post">
        <label for="selected_date">Selectionner une date :</label><br>
        <input type="date" id="selected_date" name="selected_date"><br><br>
        <input type="submit" value="Afficher la liste">
    </form>

    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouabd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_date'])) {
        $selected_date = $_POST['selected_date'];

        $stmt_students = $conn->prepare("SELECT full_name FROM etudiant WHERE date_presence = :selected_date");
        $stmt_students->bindParam(':selected_date', $selected_date);
        $stmt_students->execute();

        echo "<div class='student-list'>";
        echo "<h2>Liste des etudiants pour le " . $selected_date . "</h2>";
        echo "<table><tr><th>Nom complet de l'etudiant</th></tr>";

        while ($row = $stmt_students->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>" . $row['full_name'] . "</td></tr>";
        }
        echo "</table>";
        echo "</div>";
    }
} catch(PDOException $e) {
    echo "La requete a echoue : " . $e->getMessage();
}
?>

<h2>Mise a jour du code d'acces</h2>
<form action="professor_interface.php" method="post">
    <label for="new_code">Nouveau code d'acces :</label><br>
    <input type="text" id="new_code" name="new_code"><br><br>
    <input type="submit" value="Mettre a jour">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_code'])) {
    $new_code = $_POST['new_code'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprime l'ancien code d'acces
        $stmt_delete = $conn->prepare("DELETE FROM access_code");
        $stmt_delete->execute();

        // Ajouter le nouveau code d'acces
        $stmt_insert = $conn->prepare("INSERT INTO access_code (code) VALUES (:code)");
        $stmt_insert->bindParam(':code', $new_code);
       $stmt_insert->execute();

        echo "Le code d'acces a ete mise a jour avec succes.";
    } catch(PDOException $e) {
        echo "La requete a echoue : " . $e->getMessage();
    }
}
?>
</body>
</html>