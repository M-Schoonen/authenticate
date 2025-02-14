<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php'; // Laad Composer packages
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start de sessie
session_start();

// Controleer of er een gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Genereer een 6-cijferige verificatiecode
$verificationCode = rand(100000, 999999);
$_SESSION['verification_code'] = password_hash($verificationCode, PASSWORD_DEFAULT);
$_SESSION['verification_expires'] = time() + 300; // Code is 5 minuten geldig

// Haal e-mailadres van de gebruiker op (vervang dit met je eigen manier om e-mail te krijgen)
include 'config.php'; // Verbind met de database

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $userEmail = $user['email'];
} else {
    die("Geen geldig e-mailadres gevonden.");
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['GMAIL_USER'];
    $mail->Password = $_ENV['GMAIL_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Verzendinstellingen
    $mail->setFrom($_ENV['GMAIL_USER'], 'Jouw Naam');
    $mail->addAddress($userEmail);

    // E-mail inhoud
    $mail->isHTML(true);
    $mail->Subject = 'Jouw verificatiecode';
    $mail->Body    = "Jouw verificatiecode is: <strong>$verificationCode</strong>. Deze code is 5 minuten geldig.";

    $mail->send();
    header("Location: verify.php");
    exit();
} catch (Exception $e) {
    echo "E-mail kon niet verzonden worden. Foutmelding: {$mail->ErrorInfo}";
}
?>