<?php
// buscar_usuario.php - Este archivo maneja la búsqueda de usuarios en la base de datos

include 'conexion.php';

// Obtener los parámetros de búsqueda desde la solicitud POST
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$apellido = isset($_POST['apellido_paterno']) ? $_POST['apellido_paterno'] : '';

// Aquí debes realizar la lógica real para buscar usuarios en tu base de datos
$sql = "SELECT * FROM pacientes WHERE nombreddd LIKE '%$nombre%' OR apellido_paterno LIKE'%$apellido%'";

$result = $conn->query($sql);

// Manejar los resultados
$usuarios = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($usuarios);

// Cerrar la conexión a la base de datos
$conn->close();
?>
