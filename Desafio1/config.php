<?php
$host = 'localhost';
$dbname = 'finanzas_db';
$username = 'root'; // por defecto en XAMPP
$password = ''; // por defecto en XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error en la conexión: ' . $e->getMessage();
}
?>
