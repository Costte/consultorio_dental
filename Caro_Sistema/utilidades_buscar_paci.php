<?php
include 'conexion.php';

// Obtener el nombre del paciente enviado por AJAX
$bus_paci = isset($_POST['bus_paci']) ? $_POST['bus_paci'] : '';

// Consulta SQL para obtener la información de la cita basada en el nombre del paciente
$sql = "SELECT * FROM utilidades WHERE id_paciente IN (SELECT id FROM pacientes WHERE nombre LIKE '%$bus_paci%')";
$result = $conn->query($sql);

// Crear un array con los resultados
$usersx = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usersx[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($usersx);
?>

