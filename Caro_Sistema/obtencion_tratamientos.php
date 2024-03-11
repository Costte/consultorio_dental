<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$consulta_tratamiento = "SELECT id, nombre FROM tratamientos";
$resultado_tratamiento = $conexion->query($consulta_tratamiento);

$tratamiento = array();

while ($fila_tratamiento = $resultado_tratamiento->fetch_assoc()) {
    $id_tratamiento = $fila_tratamiento["id"];
    $nombre_tratamiento = $fila_tratamiento["nombre"];

    $tratamiento[] = array('id' => $id_tratamiento, 'nombre' => $nombre_tratamiento);
}

$conexion->close();

// Devuelve la información en formato JSON
header('Content-Type: application/json');
echo json_encode($tratamiento);
?>