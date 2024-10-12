<?php
require '../config.php';
require '../clases/Entrada.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    
    // Subir factura
    $factura = $_FILES['factura']['name'];
    $factura_tmp = $_FILES['factura']['tmp_name'];
    $ruta_factura = "../uploads/" . $factura;

    move_uploaded_file($factura_tmp, $ruta_factura);

    $entrada = new Entrada($tipo, $monto, $fecha, $ruta_factura);
    $entrada->registrarEntrada($pdo);

    echo "Entrada registrada correctamente.";
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Tipo: <input type="text" name="tipo"></label>
    <label>Monto: <input type="number" name="monto" step="0.01"></label>
    <label>Fecha: <input type="date" name="fecha"></label>
    <label>Factura: <input type="file" name="factura"></label>
    <button type="submit">Registrar Entrada</button>
</form>
    <form action="../dashboard.php" method="GET">
        <button type="submit">Regresar a Dashboard</button>
    </form>

