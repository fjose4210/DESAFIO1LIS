<?php
class Salida {
	//Propiedad para almacenar el tipo de salida,monto,fecha,factura y usuario_id.
    public $tipo;
    public $monto;
    public $fecha;
    public $factura;
    public $usuario_id;


    //Constructor de la clase Salida.
    //Asigna valores a las propiedades de la clase.
    public function __construct($tipo, $monto, $fecha, $factura, $usuario_id) {
        $this->tipo = $tipo;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->factura = $factura;
        $this->usuario_id = $usuario_id;
    }

    //Método para registrar una salida.
    public function registrarSalida($pdo) {
        $stmt = $pdo->prepare("INSERT INTO salidas (tipo, monto, fecha, factura, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura, $this->usuario_id]);
    }

    //Método estático para obtener todas las salidas.
    public static function obtenerTodas($user_id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM salidas WHERE usuario_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para eliminar una salida y su factura.
    public static function eliminarSalida($pdo, $id) {
        //Primero obtiene la ruta del archivo de la factura.
        $stmt = $pdo->prepare("SELECT factura FROM salidas WHERE id = ?");
        $stmt->execute([$id]);
        $salida = $stmt->fetch(PDO::FETCH_ASSOC);  //Obtiene los datos de la salida.

        if ($salida) {
            //Elimina la salida de la base de datos
            $stmt = $pdo->prepare("DELETE FROM salidas WHERE id = ?");
            $stmt->execute([$id]);

            //Verifica si el archivo de la factura existe en el servidor y lo elimina.
            $factura = $salida['factura'];
            if (file_exists($factura)) {
                unlink($factura);  //Elimina el archivo.
            }
        }
    }
}
?>

