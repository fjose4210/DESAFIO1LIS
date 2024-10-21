<?php
class Entrada {
    public $tipo;
    public $monto;
    public $fecha;
    public $factura;

    public function __construct($tipo, $monto, $fecha, $factura) {
        $this->tipo = $tipo;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->factura = $factura;
    }

    public function registrarEntrada($pdo) {
        $stmt = $pdo->prepare("INSERT INTO entradas (tipo, monto, fecha, factura) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura]);
    }

    public static function obtenerTodas($pdo) {
        $stmt = $pdo->query("SELECT * FROM entradas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para eliminar una entrada
    public static function eliminarEntrada($id, $pdo) {
        $stmt = $pdo->prepare("DELETE FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
