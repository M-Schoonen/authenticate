<?php
session_start();

// Controleer of de gebruiker is ingelogd en of er een verificatiecode is gegenereerd
if (!isset($_SESSION['user_id']) || !isset($_SESSION['verification_code'])) {
    header("Location: login.php");
    exit();
}

// Verwerk de verificatiecode
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredCode = $_POST['verification_code'];

    // Controleer of de code nog geldig is
    if (time() > $_SESSION['verification_expires']) {
        $error = "De verificatiecode is verlopen.";
    } elseif (password_verify($enteredCode, $_SESSION['verification_code'])) {
        // Code is correct, doorgaan naar de beveiligde pagina
        unset($_SESSION['verification_code']);
        unset($_SESSION['verification_expires']);
        header("Location: fileUploader.php");
        exit();
    } else {
        $error = "Ongeldige verificatiecode.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificatiecode</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Voer je verificatiecode in</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="verification_code" placeholder="Verificatiecode" required><br>
        <button type="submit">Verifiëren</button>
    </form>
</body>
</html>