<?php
// Conexión a la base de datos
require 'config.php';

// Definir el usuario y la contraseña
$username = 'admin';
$password = '12345';

// Hashear la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Preparar la consulta para insertar el usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (:username, :password)");
    
    // Ejecutar la consulta pasando el hash de la contraseña
    $stmt->execute([
        ':username' => $username,
        ':password' => $hashed_password
    ]);

    echo "Usuario admin insertado correctamente.";
} catch (Exception $e) {
    echo "Error al insertar el usuario: " . $e->getMessage();
}
?>
