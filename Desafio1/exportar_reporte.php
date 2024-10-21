<?php
require_once('../path_to_tcpdf/tcpdf.php'); // Asegúrate de poner la ruta correcta

if (isset($_POST['imgData'])) {
    // Crear un nuevo documento PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Tu Nombre');
    $pdf->SetTitle('Reporte de Balance Mensual');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    // Configurar la fuente
    $pdf->SetFont('helvetica', '', 12);

    // Título del reporte
    $pdf->Cell(0, 10, 'Reporte Mensual de Entradas y Salidas', 0, 1, 'C');
    $pdf->Ln(10); // Nueva línea

    // Obtener los datos enviados por POST
    $totalEntradas = $_POST['totalEntradas'];
    $totalSalidas = $_POST['totalSalidas'];
    $balance = $_POST['balance'];
    
    // Contenido del reporte
    $html = '<h2>Balance: $' . $balance . '</h2>';
    $html .= '<table border="1" cellpadding="5">';
    $html .= '<tr><th>Entradas</th><th>Salidas</th></tr>';
    $html .= '<tr>';
    $html .= '<td>Total Entradas: $' . $totalEntradas . '</td>';
    $html .= '<td>Total Salidas: $' . $totalSalidas . '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Insertar la imagen del gráfico en el PDF
    $imgData = $_POST['imgData'];
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = base64_decode($imgData);
    $pdf->Image('@' . $imgData, 40, 100, 120, 90, 'PNG'); // Posición y tamaño de la imagen

    // Cerrar y enviar el PDF para descarga
    $pdf->Output('Reporte_Balance.pdf', 'D'); // Forzar la descarga del PDF
}
?>
