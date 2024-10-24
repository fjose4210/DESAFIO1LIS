<?php
ob_start(); //Inicia el buffer de salida.
require_once('../tcpdf/tcpdf.php'); 
require_once('../clases/entrada.php'); 
require_once('../clases/salida.php'); 
require_once('../config.php'); //Configuración de la base de datos.

session_start(); //Inicia sesión si no se ha hecho.
if (!isset($_SESSION['user_id'])) {
    die("Debe iniciar sesión para ver el balance.");
}

if (isset($_POST['imgData'])) {
    //Obtiene el ID del usuario desde la sesión.
    $user_id = $_SESSION['user_id'];

    //Obtiene entradas y salidas usando la conexión $pdo del config.php.
    $entradas = Entrada::obtenerTodas($user_id, $pdo);
    $salidas = Salida::obtenerTodas($user_id, $pdo);

    //Cálculo de total de entradas y salidas.
    $totalEntradas = $_POST['totalEntradas'];
    $totalSalidas = $_POST['totalSalidas'];
    $balance = $_POST['balance'];

    $fechaActual = date('d/m/Y');

    //Crea el PDF.
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('');
    $pdf->SetTitle('Reporte de Balance Mensual');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

    //Título del PDF.
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Balance Mensual', 0, 1, 'C');
    $pdf->Cell(0, 10, $fechaActual, 0, 1, 'R'); 
    $pdf->Ln(10);

    //Título de la sección de balance.
    $pdf->SetFillColor(30, 60, 114); 
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->Cell(0, 10, 'Balance Mensual Detallado', 0, 1, 'C', 1);
    $pdf->Ln(5);

    $pdf->SetTextColor(0, 0, 0);

    // Tabla de balance en HTML.
    $html = '
        <style>
            .table {
                border: 2px solid #1e3c72;
                border-radius: 10px;
                padding: 10px;
                margin-top: 15px;
                width: 100%;
            }
            .table th, .table td, .table tr{
                border: 1px solid #1e3c72;
                padding: 8px;
                text-align: center;
            }
            .title {
                background-color: #1e3c72;
                color: #fff;
                padding: 10px;
                border-radius: 10px;
            }
            .balance-text {
                font-size: 18px;
                color: #000;
                text-align: center;
            }
        </style>
        <div class="table">
            <h3 class="balance-text">Balance: $' . $balance . '</h3>
            <table class="balance-text">
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
                <tbody>';
                
    //Añade cada fila de entradas y salidas a la tabla.
    $maxRows = max(count($entradas), count($salidas));
    for ($i = 0; $i < $maxRows; $i++) {
        $entrada = $entradas[$i] ?? null;
        $salida = $salidas[$i] ?? null;
        
        $html .= '<tr>';
        if ($entrada) {
            $html .= '<td>' . htmlspecialchars($entrada['tipo']) . '</td><td>' . htmlspecialchars($entrada['monto']) . '</td>';
        } else {
            $html .= '<td colspan="2"></td>';
        }

        if ($salida) {
            $html .= '<td>' . htmlspecialchars($salida['tipo']) . '</td><td>' . htmlspecialchars($salida['monto']) . '</td>';
        } else {
            $html .= '<td colspan="2"></td>';
        }
        $html .= '</tr>';
    }

    $html .= '
                </tbody>
            </table>
        </div>';

    //Inserta la tabla HTML en el PDF.
    $pdf->writeHTML($html, true, false, true, false, '');

    //Convierte la imagen del gráfico y la añade al PDF.
    $imgData = $_POST['imgData'];
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = base64_decode($imgData);
    $pdf->Image('@' . $imgData, 60, 150, 90, 90, 'PNG');

    //Limpia el buffer antes de enviar el PDF.
    ob_end_clean(); // Limpiar el buffer de salida.

    //Salida del PDF.
    $pdf->Output('Reporte_Balance.pdf', 'D');
}
?>
