<?php
// obtener_usuario.php - Este archivo maneja la obtención de datos del usuario por ID desde la base de datos

include 'conexion.php';

// Obtener el ID del usuario desde la solicitud GET
$idUsuario = isset($_GET['id']) ? $_GET['id'] : null;

if ($idUsuario === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de paciente válido.'
    ];
    http_response_code(400); // Bad Request
    echo json_encode($response);
    exit;
} else {
    // Aquí debes realizar la lógica real para obtener los datos del usuario de tu base de datos
    $sql = "SELECT * FROM pacientes WHERE id = $idUsuario";
    $result = $conn->query($sql);

    if (!$result) {
        $response = [
            'success' => false,
            'message' => 'Error al ejecutar la consulta: ' . $conn->error
        ];
        http_response_code(500); // Internal Server Error
        echo json_encode($response);
        exit;
    }

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        $response = [
            'success' => true,
            'data' => $usuario,
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontró al paciente con el ID proporcionado.'
        ];
        http_response_code(404); // Not Found
        echo json_encode($response);
        exit;
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>
