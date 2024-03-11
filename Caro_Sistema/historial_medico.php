<?php

include 'conexion.php';

// Obtener el ID del paciente desde la solicitud GET
$id_paciente = $_GET['id'];

// Consulta SQL para obtener los datos del historial médico del paciente
$sql = "SELECT p.nombre, p.apellido_paterno, p.apellido_materno, p.fecha_nacimiento, p.edad, p.sexo, p.fecha_ingreso, p.id_doctor, d.nombre AS nombre_doctor 
FROM pacientes p 
INNER JOIN doctores d ON p.id_doctor = d.id 
WHERE p.id = $id_paciente";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // El paciente fue encontrado, obtener sus datos
    $row = $result->fetch_assoc();

    // Crear un array con los datos del historial médico del paciente
    $historial_medico = array(
        'nombre' => $row['nombre'],
        'apellido_paterno' => $row['apellido_paterno'],
        'apellido_materno' => $row['apellido_materno'],
        'edad' => $row['edad'],
        'fecha_nacimiento' => $row['fecha_nacimiento'],
        'fecha_ingreso' => $row['fecha_ingreso'],
        'sexo' => $row['sexo'],
        'id_doctor' => $row['id_doctor']
    );

    // Devolver los datos del historial médico del paciente en formato JSON
    header('Content-Type: application/json');
    echo json_encode($historial_medico);
} else {
    // No se encontró ningún paciente con el ID proporcionado
    echo "No se encontró ningún paciente con el ID: " . $id_paciente;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
