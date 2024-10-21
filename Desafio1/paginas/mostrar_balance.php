<?php
require '../config.php';
require '../clases/entrada.php';
require '../clases/salida.php';

$entradas = Entrada::obtenerTodas($pdo);
$salidas = Salida::obtenerTodas($pdo);

$totalEntradas = array_sum(array_column($entradas, 'monto'));
$totalSalidas = array_sum(array_column($salidas, 'monto'));
$balance = $totalEntradas - $totalSalidas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Balance</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #1e3c72; 
            color: #fff;
        }
        .container {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
        }
        .table {
            width: 100%; 
            margin-top: 20px; 
        }
        #myChart {
            max-width: 400px; /* Ajustar el ancho del gráfico */
            margin: 20px auto; /* Centrar el gráfico */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center" style="color: #1e3c72;">Reporte de Balance</h2>

        <h4 class="text-center">Balance: $<?php echo $balance; ?></h4>

        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Entradas</th>
                    <th>Salidas</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php foreach ($entradas as $entrada): ?>
                            <div><?php echo htmlspecialchars($entrada['tipo']); ?>: $<?php echo htmlspecialchars($entrada['monto']); ?></div>
                        <?php endforeach; ?>
                        <strong>Total Entradas: $<?php echo $totalEntradas; ?></strong>
                    </td>
                    <td>
                        <?php foreach ($salidas as $salida): ?>
                            <div><?php echo htmlspecialchars($salida['tipo']); ?>: $<?php echo htmlspecialchars($salida['monto']); ?></div>
                        <?php endforeach; ?>
                        <strong>Total Salidas: $<?php echo $totalSalidas; ?></strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <canvas id="myChart" width="400" height="300"></canvas> <!-- Ajustar la altura del gráfico -->
        
        <!-- Botón para exportar a PDF -->
        <button id="exportarPDF" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Exportar a PDF</button>

        <form action="../dashboard.php" method="GET" class="mt-3">
            <button type="submit" class="btn btn-secondary" style="width: 100%;">Regresar a Dashboard</button>
        </form>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Entradas', 'Salidas'],
                datasets: [{
                    data: [<?php echo $totalEntradas; ?>, <?php echo $totalSalidas; ?>],
                    backgroundColor: ['#1e3c72', '#FF6384'],
                    borderColor: ['#1e3c72', '#FF6384'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });

        document.getElementById('exportarPDF').addEventListener('click', function() {
            var canvas = document.getElementById('myChart');
            var imgData = canvas.toDataURL('image/png');

            // Crear un formulario para enviar la imagen al servidor
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'exportar_reporte.php';

            // Campo oculto para pasar la imagen
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'imgData';
            input.value = imgData;

            form.appendChild(input);
            document.body.appendChild(form);

            // Enviar el formulario
            form.submit();
        });
    </script>
</body>
</html>
