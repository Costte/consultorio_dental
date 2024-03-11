<?php
// actualizar_usuario.php - Este archivo maneja la actualización de un usuario en la base de datos

include 'conexion.php';

// Obtener los datos del usuario desde la solicitud POST
$idDoc = isset($_POST['id']) ? $_POST['id'] : null;
$nuevoNombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$nuevoApellido_paterno = isset($_POST['apellido_paterno']) ? $_POST['apellido_paterno'] : '';
$nuevoApellido_materno = isset($_POST['apellido_materno']) ? $_POST['apellido_materno'] : '';
$nuevocedula = isset($_POST['cedula']) ? $_POST['cedula'] : '';
$nuevoespecialidad = isset($_POST['especialidad']) ? $_POST['especialidad'] : '';
$nuevofecha_ingreso = isset($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : '';

// Validar que se proporcionó un ID de usuario válido
if ($idDoc === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de Doctor válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para actualizar el usuario en tu base de datos
    $sql = "UPDATE doctores SET nombre = '$nuevoNombre', apellido_paterno = '$nuevoApellido_paterno', apellido_materno = '$nuevoApellido_materno', cedula = '$nuevocedula', especialidad = '$nuevoespecialidad', fecha_ingreso = '$nuevofecha_ingreso' WHERE id = $idDoc";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'success' => true,
            'message' => 'Doctor actualizado con éxito.',
            'data' => [
                'id' => $idDoc,
                'nombre' => $nuevoNombre,
                'apellido_paterno' => $nuevoApellido_paterno,
            ],
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al actualizar el Doctor: ' . $conn->error
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

//