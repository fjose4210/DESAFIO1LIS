<?php
require '../config.php';
require '../clases/entrada.php';

session_start(); // Inicia la sesión para acceder a user_id
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión para ver las entradas.");
}

$user_id = $_SESSION['user_id'];

// Comprobar si se ha enviado un ID para eliminar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Llama al método estático para eliminar la entrada
    Entrada::eliminarEntrada($id, $pdo);

    // Redirigir a la misma página después de eliminar
    header('Location: ver_entradas.php?mensaje=Entrada eliminada correctamente.');
    exit();
}

// Obtener todas las entradas para mostrar en la tabla
$entradas = Entrada::obtenerTodas($user_id, $pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas Registradas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #1e3c72; 
            color: #fff;
        }
        .container {
            padding: 30px;
            max-width: 800px; 
            margin: auto; 
            background-color: #ffffff; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center" style="color: #1e3c72;">Entradas Registradas</h2>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class='alert alert-success'><?php echo htmlspecialchars($_GET['mensaje']); ?></div>
        <?php endif; ?>

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
                <?php foreach ($entradas as $entrada): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entrada['tipo']); ?></td>
                        <td><?php echo '$ ' . htmlspecialchars(number_format($entrada['monto'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($entrada['fecha']); ?></td>
                        <td>
                            <a href="<?php echo $entrada['factura']; ?>" target="_blank">
                                <img src="<?php echo $entrada['factura']; ?>" alt="Factura" class="img-thumbnail" style="width: 50px;">
                            </a>
                        </td>
                        <td>
                            <form action="" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $entrada['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta entrada?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="../dashboard.php" method="GET" class="mt-3">
            <button type="submit" class="btn btn-secondary">Regresar a Dashboard</button>
        </form>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
