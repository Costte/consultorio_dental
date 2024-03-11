<?php
// Establecer la conexión a la base de datos (asumiendo que tienes una conexión establecida)
include 'conexion.php';

// Obtener el ID del paciente desde la solicitud GET
$idPaciente = $_GET['id'];

// Consulta SQL para obtener el nombre del paciente
$sql = "SELECT nombre, apellido_paterno FROM pacientes WHERE id = $idPaciente";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si se encuentra el paciente, devolver su nombre
    $row = $result->fetch_assoc();
    echo $row["nombre"] . " " . $row["apellido_paterno"];
} else {
    // Si no se encuentra el paciente, devolver un mensaje de error o un valor predeterminado
    echo "Paciente no encontrado";
}

// Cerrar la conexión
$conn->close();
?>

