<?php
session_start();
require 'config.php';
require 'clases/Login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = new Login();
    $user_id = $login->autenticar($_POST['username'], $_POST['password'], $pdo);

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header('Location: dashboard.php');
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>

<form method="POST">
    <label>Usuario: <input type="text" name="username"></label>
    <label>Contraseña: <input type="password" name="password"></label>
    <button type="submit">Iniciar Sesión</button>
</form>
