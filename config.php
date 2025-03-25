<?php
// Inloggevens voor de database
$host = 'localhost';
$dbname = 'fileuploader';
$user = 'root';
$pass = 'root';

// Maak verbinding met de database
$conn = new mysqli($host, $user, $pass, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>