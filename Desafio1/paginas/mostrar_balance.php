<?php
require '../config.php';
require '../clases/entrada.php';
require '../clases/salida.php';

// Obtener todas las entradas y salidas
$entradas = Entrada::obtenerTodas($pdo);
$salidas = Salida::obtenerTodas($pdo);

// Calcular el total de entradas y salidas
$total_entradas = array_sum(array_column($entradas, 'monto'));
$total_salidas = array_sum(array_column($salidas, 'monto'));

// Calcular el balance
$balance = $total_entradas - $total_salidas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Balance Mensual</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Reporte Mensual</h1>

    <!-- Mostrar las tablas de entradas y salidas -->
    <h2>Entradas</h2>
    <table border="1">
        <tr>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Fecha</th>
        </tr>
        <?php foreach ($entradas as $entrada): ?>
        <tr>
            <td><?php echo $entrada['tipo']; ?></td>
            <td><?php echo $entrada['monto']; ?></td>
            <td><?php echo $entrada['fecha']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Salidas</h2>
    <table border="1">
        <tr>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Fecha</th>
        </tr>
        <?php foreach ($salidas as $salida): ?>
        <tr>
            <td><?php echo $salida['tipo']; ?></td>
            <td><?php echo $salida['monto']; ?></td>
            <td><?php echo $salida['fecha']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h3>Balance Mensual: <?php echo $balance; ?></h3>

    <!-- Dibujar el gráfico de pastel -->
    <canvas id="balanceChart" width="400" height="400"></canvas>
    <script>
        const data = {
            labels: ['Entradas', 'Salidas'],
            datasets: [{
                data: [<?php echo $total_entradas; ?>, <?php echo $total_salidas; ?>],
                backgroundColor: ['#36a2eb', '#ff6384'],
            }]
        };

        const config = {
            type: 'pie',
            data: data,
        };

        const balanceChart = new Chart(
            document.getElementById('balanceChart'),
            config
        );
    </script>

    <!-- Botón para exportar a PDF -->
    <form action="exportar_pdf.php" method="POST">
        <button type="submit">Exportar a PDF</button>
    </form>
	
	        <form action="../dashboard.php" method="GET" class="mt-3">
            <button type="submit" class="btn btn-secondary" style="width: 100%;">Regresar a Dashboard</button>
        </form>
</body>
</html>
