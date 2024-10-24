<?php
require '../config.php'; 
require '../clases/salida.php'; 

session_start(); //Inicia la sesión para acceder a user_id.
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión para ver el balance.");
}

$user_id = $_SESSION['user_id'];

//Obtiene todas las salidas.
$salidas = Salida::obtenerTodas($user_id, $pdo);

//Elimina salida si se solicita.
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $salida = new Salida('', '', '', '', ''); 
    $salida->eliminarSalida($pdo, $id); 
    header("Location: ver_salidas.php"); 
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Salidas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1e3c72;
            color: #fff;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Salidas Registradas</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Factura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salidas as $salida): ?>
                <tr>
                    <td><?php echo htmlspecialchars($salida['tipo']); ?></td>
                    <td><?php echo '$ ' . htmlspecialchars(number_format($salida['monto'], 2)); ?></td>
                    <td><?php echo htmlspecialchars($salida['fecha']); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($salida['factura']); ?>" target="_blank">
                            <img src="<?php echo htmlspecialchars($salida['factura']); ?>" alt="Factura" width="50">
                        </a>
                    </td>
                    <td>
                        <a href="?eliminar=<?php echo $salida['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta salida?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <form action="../dashboard.php" method="GET">
        <button type="submit" class="btn btn-secondary">Regresar a Dashboard</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
