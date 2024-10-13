<?php
require '../config.php';
require '../clases/entrada.php';

$entradas = Entrada::obtenerTodas($pdo);
?>

<h2>Entradas Registradas</h2>
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
        <?php foreach ($entradas as $entrada): ?>
            <tr>
                <td><?php echo htmlspecialchars($entrada['tipo']); ?></td>
                <td><?php echo htmlspecialchars($entrada['monto']); ?></td>
                <td><?php echo htmlspecialchars($entrada['fecha']); ?></td>
                <td>
                    <a href="<?php echo $entrada['factura']; ?>" target="_blank">
                        <img src="<?php echo $entrada['factura']; ?>" alt="Factura" width="100">
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<form action="../dashboard.php" method="GET">
    <button type="submit">Regresar a Dashboard</button>
</form>
