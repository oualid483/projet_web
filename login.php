<?php
session_start();


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("location: professor_interface.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "erraji"; 
    $password = "0000"; 

    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['loggedin'] = true;
        header("location: professor_interface.php");
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Professeur</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.container {
    width: 350px;
    background-color: #fff; 
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #6699cc;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
}

form {
    text-align: center;
}

label {
    display: block;
    text-align: left;
    margin-bottom: 15px;
    color: #555; 
    font-size: 18px;
    font-weight: bold;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 25px;
    border: none;
    border-radius: 50px;
    background-color: #f8f8f8; 
    border: 2px solid #ccc; 
    font-size: 16px;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #66cccc; 
}

input[type="submit"] {
    background-color: #66cccc; 
    color: #fff; 
    padding: 15px;
    border: none;
    border-radius: 50px; 
    cursor: pointer;
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #4cbbb9; 
}

.error-message {
    color: #cc3333; 
    margin-top: 20px;
    text-align: center;
    font-size: 16px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2 style="text-align: center;">Connexion Professeur</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required><br>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>
            
            <input type="submit" value="Se connecter">
        </form>
        <?php if(isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>
    </div>
</body>
</html>