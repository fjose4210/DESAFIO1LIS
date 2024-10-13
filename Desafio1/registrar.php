<?php
session_start();
require 'config.php';
require 'clases/Registrar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Crear una instancia de la clase Register
    $register = new Register();
    
    // Intentar registrar al usuario
    $result = $register->registrarUsuario($username, $password, $repeat_password, $pdo);

   if ($result === true) {
        // Almacenar mensaje de éxito en la sesión
        $_SESSION['mensaje'] = "Usuario creado exitosamente.";
        
        // Redirigir a index.php
        header('Location: index.php');
        exit();
    } else {
        echo $result; // Mostrar el mensaje de error
    }
}
?>

<!-- Formulario de registro -->
<form method="POST">
    <label>Usuario: <input type="text" name="username"></label>
    <label>Contraseña: <input type="password" name="password"></label>
    <label>Repetir Contraseña: <input type="password" name="repeat_password"></label>
    <button type="submit">Registrar</button>
</form>