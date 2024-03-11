<?php
// eliminar_usuario.php - Este archivo maneja la eliminación de un usuario en la base de datos

include 'conexion.php';

// Obtener el ID del usuario a eliminar desde la solicitud GET
$idUsuario = isset($_GET['id']) ? $_GET['id'] : null;

// Validar que se proporcionó un ID de usuario
if ($idUsuario === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de paciente válido.'
    ];
} else {
    // Aquí debes realizar la lógica real para eliminar el usuario de tu base de datos
    $sql = "DELETE FROM pacientes WHERE id = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        $response = [
            'success' => true,
            'message' => 'Paciente eliminado con éxito.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al eliminar el paciente: ' . $conn->error
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>

