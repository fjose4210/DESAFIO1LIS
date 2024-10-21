<?php
session_start();
require 'config.php';
require 'clases/Login.php';

// Mostrar mensaje de éxito si existe
$message = "";
if (isset($_SESSION['mensaje'])) {
    $message = "<div class='alert alert-success' style='color: #1e3c72;'>" . $_SESSION['mensaje'] . "</div>";
    unset($_SESSION['mensaje']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = new Login();
    $user_id = $login->autenticar($_POST['username'], $_POST['password'], $pdo);

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        header('Location: dashboard.php');
    } else {
        $message = "<div class='alert alert-danger' style='color: #1e3c72;'>Usuario o contraseña incorrectos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1e3c72; /* Color de Fondo */
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0; /* Elimina márgenes del body */
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
            max-width: 400px; /* Ancho del contenedor */
            width: 100%;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%; /* Botón */
            background-color: #1e3c72; /* Color del botón */
            border-color: #1e3c72; /* Borde del botón */
        }

        .btn-danger {
            width: 100%; 
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center" style="color: #1e3c72; margin-bottom: 1rem;">Iniciar Sesión</h2>

        <!-- Mensaje de éxito o error -->
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
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <p class="mt-3">¿No tienes una cuenta? <a href="Registrar.php" style="color: #1e3c72;">Regístrate aquí</a></p>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
