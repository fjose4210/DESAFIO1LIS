<?php
session_start();
require 'config.php';
require 'clases/Registrar.php';

// Mensaje para el registro
$message = "";
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
        $message = "<div class='alert alert-danger' style='color: #1e3c72;'>$result</div>"; // Mostrar el mensaje de error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1e3c72; 
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0; 
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
            max-width: 400px; 
            width: 100%;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%; 
            background-color: #1e3c72; 
            border-color: #1e3c72; 
        }

        .btn-danger {
            width: 100%; 
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center" style="color: #1e3c72; margin-bottom: 1rem;">Registrarse</h2>

        <!-- Mensaje de error -->
        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="repeat_password">Repetir Contraseña:</label>
                <input type="password" class="form-control" name="repeat_password" id="repeat_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>

        <p class="mt-3">¿Ya tienes una cuenta? <a href="index.php" style="color: #1e3c72;">Inicia sesión aquí</a></p>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
