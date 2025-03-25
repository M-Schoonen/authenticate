<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Zoek gebruiker in de database, inclusief de rol
    $stmt = $conn->prepare("SELECT u.id, u.username, u.password, u.rol_id, r.rechten_niveau 
                            FROM users u
                            INNER JOIN rollen r ON u.rol_id = r.id
                            WHERE u.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Controleer het wachtwoord
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['rechten_niveau'] = $user['rechten_niveau'];  // Sla het rechten_niveau op in de sessie

        // Stuur verificatiemail (indien nodig)
        header("Location: sendVerificationEmail.php");
        exit();
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Inloggen</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required><br>
        <input type="password" name="password" placeholder="Wachtwoord" required><br>
        <button type="submit">Login</button><br>
        <a href="register.php">Registreren</a>
    </form>
</body>
</html>
