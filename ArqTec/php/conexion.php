<?php
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "mydb";

$conexion = new mysqli($host, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
