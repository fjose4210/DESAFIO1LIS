<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
echo "Bienvenido al sistema de control de finanzas";
?>

<ul>
    <li><a href="paginas/registrar_entrada.php">Registrar Entrada</a></li>
    <li><a href="paginas/registrar_salida.php">Registrar Salida</a></li>
    <li><a href="paginas/ver_entradas.php">Ver Entradas</a></li>
    <li><a href="paginas/ver_salidas.php">Ver Salidas</a></li>
    <li><a href="paginas/mostrar_balance.php">Mostrar Balance</a></li>
</ul>
