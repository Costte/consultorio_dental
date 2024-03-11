<?php
// actualizar_usuario.php - Este archivo maneja la actualización de un usuario en la base de datos

include 'conexion.php';

// Obtener los datos del usuario desde la solicitud POST
$idTrata = isset($_POST['id']) ? $_POST['id'] : null;
$nuevoNombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$nuevoPrecio = isset($_POST['costo_tratamiento']) ? $_POST['costo_tratamiento'] : '';


// Validar que se proporcionó un ID de usuario válido
if ($idTrata === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de tratamiento válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para actualizar el usuario en tu base de datos
    $sql = "UPDATE tratamientos SET nombre = '$nuevoNombre', costo_tratamiento = '$nuevoPrecio' WHERE id = $idTrata";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'success' => true,
            'message' => 'Tratamiento actualizado con éxito.',
            'data' => [
                'id' => $idTrata,
                'nombre' => $nuevoNombre,
                'costo_tratamiento' => $nuevoPrecio,
            ],
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al actualizar el tratamiento: ' . $conn->error
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

//