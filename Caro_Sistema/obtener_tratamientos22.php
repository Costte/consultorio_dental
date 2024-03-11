<?php
include 'conexion.php';

// Consulta SQL para obtener todos los tratamientos
$sql = "SELECT id, nombre FROM tratamientos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Array para almacenar los tratamientos
    $tratamientos = array();

    // Iterar sobre cada fila de resultados
    while($row = $result->fetch_assoc()) {
        // Agregar el tratamiento al array
        $tratamiento = array(
            "id" => $row["id"],
            "nombre" => $row["nombre"]
        );
        array_push($tratamientos, $tratamiento);
    }

    // Codificar los tratamientos en formato JSON y devolverlos
    echo json_encode($tratamientos);
} else {
    // No se encontraron tratamientos
    echo "No se encontraron tratamientos.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
