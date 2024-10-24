<?php
require '../config.php';
require '../clases/entrada.php';
require '../clases/salida.php';

session_start(); //Inicia la sesión para acceder a user_id.
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión para ver el balance.");
}

$user_id = $_SESSION['user_id'];
$entradas = Entrada::obtenerTodas($user_id, $pdo);
$salidas = Salida::obtenerTodas($user_id, $pdo);

$totalEntradas = array_sum(array_column($entradas, 'monto'));
$totalSalidas = array_sum(array_column($salidas, 'monto'));
$balance = $totalEntradas - $totalSalidas;

//Cálculo de los porcentajes para el gráfico.
$porcentajeEntradas = $totalEntradas > 0 ? round(($totalEntradas / ($totalEntradas + $totalSalidas)) * 100, 2) : 0;
$porcentajeSalidas = $totalSalidas > 0 ? round(($totalSalidas / ($totalEntradas + $totalSalidas)) * 100, 2) : 0;
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
            max-width: 400px;
            margin: 20px auto;
        }
        /*Estilo para el cuadro azul que encierra el gráfico*/
        .chart-container, .table-container, .title-container {
            border: 2px solid #1e3c72;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .chart-title, .title-container h2 {
            color: #1e3c72;
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .balance-text {
            text-align: center;
            font-size: 18px;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h2 class="text-center">Reporte de Balance</h2>
        </div>

        <div class="table-container">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th colspan="2">Entradas</th>
                        <th colspan="2">Salidas</th>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $maxRows = max(count($entradas), count($salidas));
                    for ($i = 0; $i < $maxRows; $i++): 
                        $entrada = $entradas[$i] ?? null; 
                        $salida = $salidas[$i] ?? null; 
                    ?>
                    <tr>
                        <?php if ($entrada): ?>
                            <td><?php echo htmlspecialchars($entrada['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($entrada['monto']); ?></td>
                        <?php else: ?>
                            <td colspan="2"></td>
                        <?php endif; ?>

                        <?php if ($salida): ?>
                            <td><?php echo htmlspecialchars($salida['tipo']); ?></td>
                            <td><?php echo htmlspecialchars($salida['monto']); ?></td>
                        <?php else: ?>
                            <td colspan="2"></td>
                        <?php endif; ?>
                    </tr>
                    <?php endfor; ?>
                    <tr>
                        <td><strong>Total Entradas</strong></td>
                        <td><strong>$<?php echo $totalEntradas; ?></strong></td>
                        <td><strong>Total Salidas</strong></td>
                        <td><strong>$<?php echo $totalSalidas; ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <h4 class="balance-text">Balance: $<?php echo $balance; ?></h4>
        </div>

        <!--Contenedor del gráfico-->
        <div class="chart-container">

            <h4 class="chart-title">Gráfico mensual Entradas vs Salidas</h4>

            <canvas id="myChart" width="400" height="300"></canvas>
        </div>

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
                labels: ['Entradas (<?php echo $porcentajeEntradas; ?>%)', 'Salidas (<?php echo $porcentajeSalidas; ?>%)'],
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
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                var value = tooltipItem.raw;
                                var dataset = tooltipItem.chart.data.datasets[0].data;
                                var total = dataset.reduce(function(accumulator, currentValue) {
                                    return accumulator + currentValue;
                                }, 0);
                                var percentage = ((value / total) * 100).toFixed(2);
                                return tooltipItem.label + ': $' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        document.getElementById('exportarPDF').addEventListener('click', function() {
            var canvas = document.getElementById('myChart');
            var imgData = canvas.toDataURL('image/png');

            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'exportar_reporte.php';

            var inputImg = document.createElement('input');
            inputImg.type = 'hidden';
            inputImg.name = 'imgData';
            inputImg.value = imgData;
            form.appendChild(inputImg);

            var inputEntradas = document.createElement('input');
            inputEntradas.type = 'hidden';
            inputEntradas.name = 'totalEntradas';
            inputEntradas.value = "<?php echo $totalEntradas; ?>";
            form.appendChild(inputEntradas);

            var inputSalidas = document.createElement('input');
            inputSalidas.type = 'hidden';
            inputSalidas.name = 'totalSalidas';
            inputSalidas.value = "<?php echo $totalSalidas; ?>";
            form.appendChild(inputSalidas);

            var inputBalance = document.createElement('input');
            inputBalance.type = 'hidden';
            inputBalance.name = 'balance';
            inputBalance.value = "<?php echo $balance; ?>";
            form.appendChild(inputBalance);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
</body>
</html>
