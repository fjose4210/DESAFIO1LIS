<?php
require '../config.php';
require '../clases/salida.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar tipo
    if (empty($_POST['tipo'])) {
        $errores[] = "El campo 'Tipo' es obligatorio.";
    } else {
        $tipo = $_POST['tipo'];
    }

    // Validar monto
    if (empty($_POST['monto'])) {
        $errores[] = "El campo 'Monto' es obligatorio.";
    } elseif (!is_numeric($_POST['monto']) || $_POST['monto'] <= 0) {
        $errores[] = "El monto debe ser un número positivo.";
    } else {
        $monto = $_POST['monto'];
    }

    // Validar fecha
    if (empty($_POST['fecha'])) {
        $errores[] = "El campo 'Fecha' es obligatorio.";
    } else {
        $fecha = $_POST['fecha'];
    }

    // Validar factura (archivo)
    if (empty($_FILES['factura']['name'])) {
        $errores[] = "El campo 'Factura' es obligatorio.";
    } else {
        $factura = $_FILES['factura']['name'];
        $factura_tmp = $_FILES['factura']['tmp_name'];
        $ruta_factura = "../uploads/" . $factura;
    }

    // Si no hay errores, registrar la salida
    if (empty($errores)) {
        move_uploaded_file($factura_tmp, $ruta_factura);

        $salida = new Salida($tipo, $monto, $fecha, $ruta_factura);
        $salida->registrarSalida($pdo);

        echo "Salida registrada correctamente.";
    } else {
        // Mostrar los errores
        foreach ($errores as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Tipo: <input type="text" name="tipo" value="<?php echo isset($_POST['tipo']) ? htmlspecialchars($_POST['tipo']) : ''; ?>"></label><br>
    <label>Monto: <input type="number" name="monto" step="0.01" min="0" value="<?php echo isset($_POST['monto']) ? htmlspecialchars($_POST['monto']) : ''; ?>"></label><br>
    <label>Fecha: <input type="date" name="fecha" value="<?php echo isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : ''; ?>"></label><br>
    <label>Factura: <input type="file" name="factura"></label><br>
    <button type="submit">Registrar Salida</button>
</form>

<form action="../dashboard.php" method="GET">
    <button type="submit">Regresar a Dashboard</button>
</form>
