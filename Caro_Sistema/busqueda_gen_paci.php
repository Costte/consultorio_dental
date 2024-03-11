<?php

include 'conexion.php';

// Consulta para obtener todos los usuarios
$sql = "SELECT pacientes.*, tratamientos.nombre as nombre_tratamiento
FROM pacientes
LEFT JOIN tratamientos ON pacientes.Id_tratamiento = tratamientos.id";
$result = $conn->query($sql);

// Crear un array con los resultados
$users = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($users);
?>