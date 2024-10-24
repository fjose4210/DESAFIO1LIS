<?php
require '../config.php';
require '../clases/entrada.php';

$errores = [];
$mensajeExito = '';

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión para registrar una entrada.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Valida tipo.
    if (empty($_POST['tipo'])) {
        $errores[] = "El campo 'Tipo' es obligatorio.";
    } else {
        $tipo = $_POST['tipo'];
    }

    //Valida monto.
    if (empty($_POST['monto'])) {
        $errores[] = "El campo 'Monto' es obligatorio.";
    } elseif (!is_numeric($_POST['monto']) || $_POST['monto'] <= 0) {
        $errores[] = "El monto debe ser un número positivo.";
    } else {
        $monto = $_POST['monto'];  //Asigna monto.
    }

    //Valida fecha.
    if (empty($_POST['fecha'])) {
        $errores[] = "El campo 'Fecha' es obligatorio.";
    } else {
        $fecha = $_POST['fecha'];
    }

    //Valida factura (archivo).
    if (empty($_FILES['factura']['name'])) {
        $errores[] = "El campo 'Factura' es obligatorio.";
    } else {
        $factura = $_FILES['factura']['name'];
        $factura_tmp = $_FILES['factura']['tmp_name'];
        $ruta_factura = "../uploads/Entradas/" . $factura;
    }

    //Si no hay errores, registra la entrada.
    if (empty($errores)) {
        move_uploaded_file($factura_tmp, $ruta_factura);

        $usuario_id = $_SESSION['user_id'];
        
        $entrada = new Entrada($tipo, $monto, $fecha, $ruta_factura, $usuario_id);
        $entrada->registrarEntrada($pdo);
        $mensajeExito = "Entrada registrada correctamente.";
    } 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada</title>
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
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center" style="color: #1e3c72; margin-bottom: 1rem;">Registrar Entrada</h2>

        <?php 
        //Muestra el mensaje de éxito.
        if (!empty($mensajeExito)) {
            echo "<div class='alert alert-success' role='alert' style='margin-bottom: 20px;'>$mensajeExito</div>";
        }

        //Muestra los errores directamente.
        if (!empty($errores)) {
            foreach ($errores as $error) {
                echo "<div class='alert alert-danger' role='alert' style='margin-bottom: 20px;'>$error</div>";
            }
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <input type="text" class="form-control" name="tipo" id="tipo" value="<?php echo isset($_POST['tipo']) ? htmlspecialchars($_POST['tipo']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="monto">Monto:</label>
                <input type="number" class="form-control" name="monto" id="monto" step="0.01" min="0" value="<?php echo isset($_POST['monto']) ? htmlspecialchars($_POST['monto']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="factura">Factura:</label>
                <input type="file" class="form-control" name="factura" id="factura" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Entrada</button>
        </form>

        <form action="../dashboard.php" method="GET" class="mt-3">
            <button type="submit" class="btn btn-secondary" style="width: 100%;">Regresar a Dashboard</button>
        </form>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
