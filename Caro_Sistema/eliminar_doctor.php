<?php
// eliminar_usuario.php - Este archivo maneja la eliminación de un usuario en la base de datos

include 'conexion.php';

// Obtener el ID del usuario a eliminar desde la solicitud GET
$idDoc = isset($_GET['id']) ? $_GET['id'] : null;

// Validar que se proporcionó un ID de usuario
if ($idDoc === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de Doctor válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para eliminar el usuario de tu base de datos
    $sql = "DELETE FROM doctores WHERE id = $idDoc";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'success' => true,
            'message' => 'Doctor eliminado con éxito.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al eliminar el Doctor: ' . $conn->error
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>