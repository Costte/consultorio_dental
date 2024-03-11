<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$consulta_doctores = "SELECT id, nombre, apellido_paterno FROM doctores";
$resultado_doctores = $conexion->query($consulta_doctores);

$doctores = array();

while ($fila_doctor = $resultado_doctores->fetch_assoc()) {
    $id_doctor = $fila_doctor["id"];
    $nombre_doctor = $fila_doctor["nombre"];
    $apellido_paterno = $fila_doctor["apellido_paterno"];
    $nombre_completo = $nombre_doctor . " " . $apellido_paterno;

    $doctores[] = array('id' => $id_doctor, 'nombre_completo' => $nombre_completo);
}

$conexion->close();

// Devuelve la información en formato JSON
header('Content-Type: application/json');
echo json_encode($doctores);
?>
