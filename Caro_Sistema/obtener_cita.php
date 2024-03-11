<?php
// obtener_usuario.php - Este archivo maneja la obtención de datos del usuario por ID desde la base de datos

include 'conexion.php';

// Obtener el ID del usuario desde la solicitud GET
$idCita = isset($_GET['id']) ? $_GET['id'] : null;

// Validar que se proporcionó un ID de usuario
if ($idCita === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de cita válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para obtener los datos del usuario de tu base de datos
    $sql = "SELECT * FROM citas WHERE id = $idCita";
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
            'message' => 'No se encontró al valido con el ID proporcionado.'
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>