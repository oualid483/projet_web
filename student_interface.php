<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Etudiant</title>
    <style>
        body {
            background-color: #f0f0f0; 
            font-family: Arial, sans-serif;
            color: #333; 
            text-align: center;
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
    </style>
</head>
<body>
    <h2>Saisie de presence</h2>
    <form action="student_interface.php" method="post">
        <label for="full_name">Nom complet :</label><br>
        <input type="text" id="full_name" name="full_name" required><br><br>

        <label for="access_code">Code d'acces :</label><br>
        <input type="text" id="access_code" name="access_code" required><br><br>

        <input type="submit" value="Soumettre">
    </form>
</body>
</html>

<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ouabd";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['full_name']) && isset($_POST['access_code'])) {
            $full_name = $_POST['full_name'];
            $access_code = $_POST['access_code'];
            $date_selected = date("Y-m-d");

            $stmt_access_code = $conn->prepare("SELECT * FROM access_code WHERE code = :code");
            $stmt_access_code->bindParam(':code', $access_code);
            $stmt_access_code->execute();
            $row_access_code = $stmt_access_code->fetch(PDO::FETCH_ASSOC);

            if (!$row_access_code) {
                echo "Code d'acces invalide.";
            } else {
                $stmt_insert_student = $conn->prepare("INSERT INTO etudiant (full_name, date_presence) VALUES (:full_name, :date_selected)");
                $stmt_insert_student->bindParam(':full_name', $full_name);
                $stmt_insert_student->bindParam(':date_selected', $date_selected);
                $stmt_insert_student->execute();

                echo "Votre présence a ete enregistree avec succes pour le " . $date_selected . ".";
            }
        } else {
            echo "Tous les champs doivent etre remplis.";
        }
    }
} catch(PDOException $e) {
    echo "La requete a echoue : " . $e->getMessage();
}
?>
