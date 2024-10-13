<?php
session_start();
require 'config.php';
require 'clases/Login.php';

// Mostrar mensaje de éxito si existe
if (isset($_SESSION['mensaje'])) {
    echo "<p style='color: green;'>" . $_SESSION['mensaje'] . "</p>";
    // Eliminar el mensaje para que no aparezca nuevamente
    unset($_SESSION['mensaje']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = new Login();
    $user_id = $login->autenticar($_POST['username'], $_POST['password'], $pdo);

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header('Location: dashboard.php');
    } else {
        echo "<p style='color: red;'>Usuario o contraseña incorrectos.</p>";
    }
}
?>

<form method="POST">
    <label>Usuario: <input type="text" name="username"></label>
    <label>Contraseña: <input type="password" name="password"></label>
    <button type="submit">Iniciar Sesión</button>
</form>

<p>¿No tienes una cuenta? <a href="Registrar.php">Regístrate aquí</a></p>
