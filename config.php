<?php
$host = 'localhost';
$dbname = 'fileuploader';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>