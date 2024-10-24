<?php
class ReporteBalance {
    public function calcularBalance($pdo) {
		
        //Obtiene suma total de entradas.
        $stmt_entradas = $pdo->query("SELECT SUM(monto) as total_entradas FROM entradas");
        $total_entradas = $stmt_entradas->fetch()['total_entradas'];

        //Obtiene suma total de salidas.
        $stmt_salidas = $pdo->query("SELECT SUM(monto) as total_salidas FROM salidas");
        $total_salidas = $stmt_salidas->fetch()['total_salidas'];

        //Calcula el balance.
        return $total_entradas - $total_salidas;
    }
}
?>
