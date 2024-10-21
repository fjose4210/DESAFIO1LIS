<?php
require_once('../tcpdf/tcpdf.php');
require '../config.php';
require '../clases/entrada.php';
require '../clases/salida.php';

// Obtener los datos
$entradas = Entrada::obtenerTodas($pdo);
$salidas = Salida::obtenerTodas($pdo);
$totalEntradas = array_sum(array_column($entradas, 'monto'));
$totalSalidas = array_sum(array_column($salidas, 'monto'));
$balance = $totalEntradas - $totalSalidas;

// Crear el PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Reporte de Balance');
$pdf->SetHeaderData('', 0, 'Reporte de Balance', 'Generado el ' . date('Y-m-d H:i:s'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Contenido HTML
$html = '
<h2>Reporte de Balance</h2>
<h4>Balance: $' . number_format($balance, 2) . '</h4>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Entradas</th>
            <th>Salidas</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>';
            
foreach ($entradas as $entrada) {
    $html .= htmlspecialchars($entrada['tipo']) . ": $" . htmlspecialchars(number_format($entrada['monto'], 2)) . '<br>';
}
$html .= '<strong>Total Entradas: $' . number_format($totalEntradas, 2) . '</strong>
            </td>
            <td>';

foreach ($salidas as $salida) {
    $html .= htmlspecialchars($salida['tipo']) . ": $" . htmlspecialchars(number_format($salida['monto'], 2)) . '<br>';
}
$html .= '<strong>Total Salidas: $' . number_format($totalSalidas, 2) . '</strong>
            </td>
        </tr>
    </tbody>
</table>
<br>
';

// Agregar contenido HTML al PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Generar gráfico
$chartData = [
    'labels' => ['Entradas', 'Salidas'],
    'data' => [$totalEntradas, $totalSalidas]
];

// Crear el gráfico
$im = imagecreate(400, 300); // Cambia el tamaño del gráfico aquí
$background = imagecolorallocate($im, 255, 255, 255); // Fondo blanco
$blue = imagecolorallocate($im, 30, 60, 114); // Color azul para las entradas
$red = imagecolorallocate($im, 255, 99, 132); // Color rojo para las salidas
$textColor = imagecolorallocate($im, 0, 0, 0); // Color negro para el texto

// Crear el gráfico de pastel
$angle1 = 0; // Ángulo inicial
$total = array_sum($chartData['data']); // Calcular el total para el gráfico

for ($i = 0; $i < count($chartData['data']); $i++) {
    $angle2 = $angle1 + ($chartData['data'][$i] / $total * 360); // Ángulo final
    imagefilledarc($im, 200, 150, 300, 300, $angle1, $angle2, ($i == 0 ? $blue : $red), IMG_ARC_PIE);
    
    // Escribir el total de entradas y salidas en el gráfico
    $totalText = $i == 0 ? 'Total Entradas: $' . number_format($chartData['data'][$i], 2) : 'Total Salidas: $' . number_format($chartData['data'][$i], 2);
    $midAngle = ($angle1 + $angle2) / 2;
    $x = 200 + (150 * cos(deg2rad($midAngle)));
    $y = 150 + (150 * sin(deg2rad($midAngle)));
    imagestring($im, 5, $x, $y, $totalText, $textColor); // Texto en negro
    $angle1 = $angle2; // Actualizar el ángulo inicial
}

// Guardar la imagen del gráfico
$chartPath = '../chart.png';
imagepng($im, $chartPath);
imagedestroy($im);

// Agregar el gráfico al PDF
$pdf->Image($chartPath, 10, $pdf->GetY(), 100, 75, 'PNG');

// Cerrar y generar PDF
$pdf->Output('reporte_balance.pdf', 'D');
?>
