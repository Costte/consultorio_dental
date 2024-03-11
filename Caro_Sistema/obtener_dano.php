<?php
// Obtener el id_paciente desde la solicitud POST de manera segura
if(isset($_POST['id_paciente']) && !empty($_POST['id_paciente'])){
    $id_paciente = $_POST['id_paciente'];

    // conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "consultorio_dental";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Preparar la consulta SQL
    $sql = "SELECT numero_diente, seccion, numero_sec, tipo_dano FROM danos_dientes WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación de la consulta fue exitosa
    if($stmt === false) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("i", $id_paciente);

    // Ejecutar la consulta
    if(!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Obtener el resultado de la consulta
    $resultado = $stmt->get_result();

    // Verificar si hay resultados
    if ($resultado->num_rows > 0) {
        // Array para almacenar las asignaciones de daños
        $asignaciones_danos = array();
        // Obtener cada fila de resultados
        while($fila = $resultado->fetch_assoc()) {
            $asignaciones_danos[] = $fila;
        }
        // Devolver las asignaciones de daños como JSON
        echo json_encode($asignaciones_danos);
    } else {
        echo "0 resultados";
    }

    // Cerrar las declaraciones y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "ID de paciente no válido.";
}
?>

