<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_cita"]) && isset($_POST["comentario"])) {
    // Obtener los datos del formulario
    $idCita = $_POST["id_cita"];
    $comentario = $_POST["comentario"];

    // Conexión a la base de datos
    include 'conexion.php';

    // Preparar la consulta SQL para actualizar el comentario
    $sql = "UPDATE citas SET comentarios = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $comentario, $idCita);

    // Ejecutar la consulta y verificar si se realizó correctamente
    if ($stmt->execute()) {
        // Comentario guardado correctamente
        echo "Comentario guardado correctamente.";
    } else {
        // Error al guardar el comentario
        echo "Error al guardar el comentario: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    // Mostrar mensaje de error si los datos del formulario no están presentes
    echo "Error: Los datos del formulario no están presentes o la solicitud no es válida.";
}
?>
