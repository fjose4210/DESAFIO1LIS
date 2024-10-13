<?php
class Register {
    public function registrarUsuario($username, $password, $repeat_password, $pdo) {
        // Verificar que las contraseñas coincidan
        if ($password !== $repeat_password) {
            return "Las contraseñas no coinciden.";
        }

        // Verificar si el nombre de usuario ya está registrado
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            return "El nombre de usuario ya está en uso.";
        }

        // Encriptar la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);

        return true; // Registro exitoso
    }
}
?>
