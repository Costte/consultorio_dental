<?php

include 'conexion.php';

// Consulta para obtener todos los usuarios
$sql = "SELECT * FROM tratamientos";
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