<?php

include 'conexion.php';

// Obtener el ID de la cita enviado por AJAX
$bus_inve = isset($_POST['bus_inve']) ? $_POST['bus_inve'] : '';

// Escapar el valor para evitar inyección SQL
$bus_inve_escapado = $conn->real_escape_string($bus_inve);

// Consulta SQL para obtener la información de la cita
$sql = "SELECT * FROM utilidades WHERE nombre_inventario = '$bus_inve_escapado'";
$result = $conn->query($sql);

// Crear un array con los resultados
$usersh = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usersh[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($usersh);
?>
