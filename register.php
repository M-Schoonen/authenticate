<?php
// Maak verbinding met de database
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Stel de standaard rol in (bijv. 'lid', rol_id = 1)
    $defaultRoleId = 1; // 1 is voor 'lid', stel dit in als je rol_id wilt gebruiken

    // Controleer of gebruikersnaam of e-mail al bestaat
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Gebruikersnaam of e-mailadres is al in gebruik.";
    } else {
        // Voeg gebruiker toe aan de database met rol_id
        $stmt = $conn->prepare("INSERT INTO users (username, email, firstName, lastName, password, rol_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $username, $email, $firstName, $lastName, $password, $defaultRoleId);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Er is een fout opgetreden. Probeer opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Registreren</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required><br>
        <input type="email" name="email" placeholder="E-mailadres" required><br>
        <input type="text" name="firstName" placeholder="Voornaam" required><br>
        <input type="text" name="lastName" placeholder="Achternaam" required><br>
        <input type="password" name="password" placeholder="Wachtwoord" required><br>
        <button type="submit">Registreer</button><br>
        <a href="login.php">Login</a>
    </form>
</body>
</html>