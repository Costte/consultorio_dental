<?php
// obtener_usuario.php - Este archivo maneja la obtención de datos del usuario por ID desde la base de datos

include 'conexion.php';

// Obtener el ID del usuario desde la solicitud GET
$idTrata = isset($_GET['id']) ? $_GET['id'] : null;

// Validar que se proporcionó un ID de usuario
if ($idTrata === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de tratamiento válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para obtener los datos del usuario de tu base de datos
    $sql = "SELECT * FROM tratamientos WHERE id = $idTrata";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $response = [
            'success' => true,
            'data' => $usuario,
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontró al tratamiento con el ID proporcionado.'
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>