<?php
class Salida {
    public $tipo;
    public $monto;
    public $fecha;
    public $factura;
    public $usuario_id;

    public function __construct($tipo, $monto, $fecha, $factura, $usuario_id) {
        $this->tipo = $tipo;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->factura = $factura;
        $this->usuario_id = $usuario_id;
    }

    //Método para registrar una salida
    public function registrarSalida($pdo) {
        $stmt = $pdo->prepare("INSERT INTO salidas (tipo, monto, fecha, factura, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura, $this->usuario_id]);
    }

    //Método estático para obtener todas las salidas
    public static function obtenerTodas($user_id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM salidas WHERE usuario_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para eliminar una salida y su factura
    public static function eliminarSalida($pdo, $id) {
        //Primero obtenemos la ruta del archivo de la factura
        $stmt = $pdo->prepare("SELECT factura FROM salidas WHERE id = ?");
        $stmt->execute([$id]);
        $salida = $stmt->fetch(PDO::FETCH_ASSOC);  //Obtenemos los datos de la salida

        if ($salida) {
            //Eliminar la salida de la base de datos
            $stmt = $pdo->prepare("DELETE FROM salidas WHERE id = ?");
            $stmt->execute([$id]);

            //Verificar si el archivo de la factura existe en el servidor y eliminarlo
            $factura = $salida['factura'];
            if (file_exists($factura)) {
                unlink($factura);  //Eliminar el archivo
            }
        }
    }
}
?>

