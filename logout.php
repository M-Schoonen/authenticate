<?php
// Start de sessie
session_start();

// Vernietig de sessie
session_unset();
session_destroy();

// Redirect naar de loginpagina
header("Location: login.php");
exit();
?>