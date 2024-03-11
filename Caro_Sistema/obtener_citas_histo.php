<?php

include 'conexion.php';

// Obtener el ID de la cita enviado por AJAX
$id_paciente = $_GET['id_paciente'];

// Escapar el valor para evitar inyección SQL

// Consulta SQL para obtener la información de la cita
$sql = "SELECT * FROM citas WHERE id_paciente = '$id_paciente'";
$result = $conn->query($sql);

// Crear un array con los resultados
$usersd = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usersd[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($usersd);
?>
