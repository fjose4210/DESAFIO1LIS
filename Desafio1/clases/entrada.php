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

    //Método para registrar una nueva entrada en la base de datos
    public function registrarEntrada($pdo) {
        $stmt = $pdo->prepare("INSERT INTO entradas (tipo, monto, fecha, factura) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura]);
    }

    //Método para obtener todas las entradas
    public static function obtenerTodas($pdo) {
        $stmt = $pdo->query("SELECT * FROM entradas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para eliminar una entrada
    public static function eliminarEntrada($id, $pdo) {
        //Obtener la ruta del archivo de la factura antes de eliminar la entrada
        $stmt = $pdo->prepare("SELECT factura FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
        $entrada = $stmt->fetch(PDO::FETCH_ASSOC);  //Aquí obtenemos la entrada que vamos a eliminar

        if ($entrada) {
            //Eliminar la entrada de la base de datos
            $stmt = $pdo->prepare("DELETE FROM entradas WHERE id = ?");
            $stmt->execute([$id]);

            //Verificar si el archivo de la factura existe en el servidor y eliminarlo
            $factura = $entrada['factura'];
            if (file_exists($factura)) {
                unlink($factura);  //Eliminar el archivo
            }
        }
    }
}
?>

