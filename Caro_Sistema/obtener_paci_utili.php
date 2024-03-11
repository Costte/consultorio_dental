<?php
// Configurar la conexión a la base de datos (ajusta estos valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

// Obtener el ID del paciente de la solicitud AJAX
$pacienteId = $_GET['id'];

// Crear una conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Consulta SQL para obtener los datos del paciente
$sql = "SELECT nombre, apellido_paterno, apellido_materno FROM pacientes WHERE id = $pacienteId";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Convertir los datos del paciente a un array asociativo
    $row = $result->fetch_assoc();
    
    // Convertir el array asociativo a JSON y devolverlo
    echo json_encode($row);
} else {
    // Si no se encuentra ningún paciente con el ID dado, devolver un JSON vacío o un mensaje de error
    echo "{}"; // JSON vacío
    // echo json_encode(["error" => "Paciente no encontrado"]); // Mensaje de error
}

$conn->close();
?>
