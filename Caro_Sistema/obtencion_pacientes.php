<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$consulta_pacientes = "SELECT id, nombre, apellido_paterno, apellido_materno FROM pacientes";
$resultado_pacientes = $conexion->query($consulta_pacientes);

$pacientes = array();

while ($fila_paciente = $resultado_pacientes->fetch_assoc()) {
    $id_paciente = $fila_paciente["id"];
    $nombre_paciente = $fila_paciente["nombre"];
    $apellido_paterno = $fila_paciente["apellido_paterno"];
    $apellido_materno = $fila_paciente["apellido_materno"];
    $nombre_completo = $nombre_paciente . " " . $apellido_paterno . " " . $apellido_materno;

    $pacientes[] = array('id' => $id_paciente, 'nombre_completo' => $nombre_completo);


    
}

$conexion->close();

// Devuelve la información en formato JSON
header('Content-Type: application/json');
echo json_encode($pacientes);
?>
