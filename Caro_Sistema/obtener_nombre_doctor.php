<?php
// Conexión a la base de datos
include 'conexion.php';
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del doctor desde la solicitud GET
$idDoctor = $_GET['id'];

// Consulta para obtener la información del doctor
$sql = "SELECT nombre, apellido_paterno, apellido_materno FROM doctores WHERE id = $idDoctor";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si se encontró al menos un resultado, devolver la información del doctor en formato JSON
    $row = $result->fetch_assoc();
    $doctor = array(
        'nombre' => $row['nombre'],
        'apellido_paterno' => $row['apellido_paterno'],
        'apellido_materno' => $row['apellido_materno']
    );
    echo json_encode($doctor);
} else {
    // Si no se encontraron resultados, devolver un objeto vacío
    echo json_encode(array());
}

// Cerrar conexión a la base de datos
$conn->close();
?>

