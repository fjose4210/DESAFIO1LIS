<?php
require_once('../tcpdf/tcpdf.php'); 

if (isset($_POST['imgData'])) {
    
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('');
    $pdf->SetTitle('Reporte de Balance Mensual');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();

   
    $pdf->SetFont('helvetica', '', 12);

    
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Balance Mensual', 0, 1, 'C');
    $pdf->Ln(10); 

    
    $totalEntradas = $_POST['totalEntradas'];
    $totalSalidas = $_POST['totalSalidas'];
    $balance = $_POST['balance'];

    
    $pdf->SetFillColor(30, 60, 114); 
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->Cell(0, 10, 'Balance Mensual Detallado', 0, 1, 'C', 1); 
    $pdf->Ln(5); 

   
    $pdf->SetTextColor(0, 0, 0);

   
    $pdf->SetFont('helvetica', '', 14);
    $pdf->SetFillColor(230, 230, 230); 
    $pdf->SetTextColor(0, 0, 0); 


    $html = '
        <style>
            .table {
                border: 2px solid #1e3c72;
                border-radius: 10px;
                padding: 10px;
                margin-top: 15px;
                width: 100%;
            }
            .table th, .table td {
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
            <table>
                <thead>
                    <tr>
                        <th>Total Entradas</th>
                        <th>Total Salidas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$' . $totalEntradas . '</td>
                        <td>$' . $totalSalidas . '</td>
                    </tr>
                </tbody>
            </table>
        </div>';


    $pdf->writeHTML($html, true, false, true, false, '');

    
    $imgData = $_POST['imgData'];
    $imgData = str_replace('data:image/png;base64,', '', $imgData);
    $imgData = base64_decode($imgData);
    $pdf->Image('@' . $imgData, 60, 150, 90, 90, 'PNG'); 

  
    $pdf->Output('Reporte_Balance.pdf', 'D'); 
}
?>
