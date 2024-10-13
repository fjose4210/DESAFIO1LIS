<?php
session_start();
session_unset(); // Limpiar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Guardar el mensaje de "sesión cerrada" en una variable de sesión
session_start(); // Iniciar nuevamente la sesión para guardar el mensaje
$_SESSION['mensaje'] = "Haz cerrado sesión exitosamente.";

// Redirigir al login (index.php)
header('Location: index.php');
exit();
