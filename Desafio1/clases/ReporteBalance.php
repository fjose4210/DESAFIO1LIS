<?php
class ReporteBalance {
    public function calcularBalance($pdo) {
        // Obtener suma total de entradas
        $stmt_entradas = $pdo->query("SELECT SUM(monto) as total_entradas FROM entradas");
        $total_entradas = $stmt_entradas->fetch()['total_entradas'];

        // Obtener suma total de salidas
        $stmt_salidas = $pdo->query("SELECT SUM(monto) as total_salidas FROM salidas");
        $total_salidas = $stmt_salidas->fetch()['total_salidas'];

        // Calcular el balance
        return $total_entradas - $total_salidas;
    }
}
?>
