<?php
require '../config.php';
require '../clases/salida.php';

$salidas = Salida::obtenerTodas($pdo);
?>

<h2>Salidas Registradas</h2>
<table border="1">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Factura</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($salidas as $salida): ?>
            <tr>
                <td><?php echo htmlspecialchars($salida['tipo']); ?></td>
                <td><?php echo htmlspecialchars($salida['monto']); ?></td>
                <td><?php echo htmlspecialchars($salida['fecha']); ?></td>
                <td>
                    <a href="<?php echo $salida['factura']; ?>" target="_blank">
                        <img src="<?php echo $salida['factura']; ?>" alt="Factura" width="100">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="../dashboard.php" method="GET">
    <button type="submit">Regresar a Dashboard</button>
</form>
