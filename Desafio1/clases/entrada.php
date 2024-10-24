<?php

class Entrada {
	//Propiedad para almacenar el tipo de entrada,monto,fecha,factura y usuario_id.
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

    //Método para registrar una nueva entrada en la base de datos.
    public function registrarEntrada($pdo) {
        $stmt = $pdo->prepare("INSERT INTO entradas (tipo, monto, fecha, factura, usuario_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->tipo, $this->monto, $this->fecha, $this->factura, $this->usuario_id]);
    }

    //Método para obtener todas las entradas.
    public static function obtenerTodas($user_id, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM entradas WHERE usuario_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Método para eliminar una entrada.
    public static function eliminarEntrada($id, $pdo) {
        //Obtiene la ruta del archivo de la factura antes de eliminar la entrada.
        $stmt = $pdo->prepare("SELECT factura FROM entradas WHERE id = ?");
        $stmt->execute([$id]);
        $entrada = $stmt->fetch(PDO::FETCH_ASSOC);  //Aquí obtenemos la entrada que vamos a eliminar.

        if ($entrada) {
            //Elimina la entrada de la base de datos.
            $stmt = $pdo->prepare("DELETE FROM entradas WHERE id = ?");
            $stmt->execute([$id]);

            //Verifica si el archivo de la factura existe en el servidor y lo elimina.
            $factura = $entrada['factura'];
            if (file_exists($factura)) {
                unlink($factura);  //Elimina el archivo.
            }
        }
    }
}
?>

