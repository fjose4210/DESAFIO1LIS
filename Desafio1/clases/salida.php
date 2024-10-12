<?php
class Salida {
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

    public function registrarSalida($pdo) {
        $stmt = $pdo->prepare("INSERT INTO salidas (tipo, monto, fecha, factura) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura]);
    }

    public static function obtenerTodas($pdo) {
        $stmt = $pdo->query("SELECT * FROM salidas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
