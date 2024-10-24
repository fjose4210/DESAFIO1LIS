<?php
session_start();
session_unset(); //Limpia todas las variables de sesión.
session_destroy(); //Destruye la sesión.

//Guarda el mensaje de "sesión cerrada" en una variable de sesión.
session_start(); //Inicia nuevamente la sesión para guardar el mensaje.
$_SESSION['mensaje'] = "Haz cerrado sesión exitosamente.";

//Redirige al login (index.php).
header('Location: index.php');
exit();
