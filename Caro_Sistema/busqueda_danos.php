<?php
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

// Obtener los parámetros de la solicitud GET y validarlos
$idPaciente = isset($_GET['id_paciente']) ? intval($_GET['id_paciente']) : 0;
$numeroDiente = isset($_GET['numeroDiente']) ? intval($_GET['numeroDiente']) : 0;

// Validar los parámetros recibidos
if ($idPaciente <= 0 || $numeroDiente <= 0) {
    die("ID de paciente o número de diente no válidos.");
}

// Consulta SQL para obtener los datos de la tabla de la base de datos
$sql = "SELECT * FROM danos_dientes WHERE id_paciente = $idPaciente AND numero_diente = $numeroDiente";
$resultado = $conn->query($sql);

// Crear un array para almacenar los resultados
$datos = array();

// Comprobar si se obtuvieron resultados
if ($resultado) {
    // Iterar sobre los resultados y agregarlos al array
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
} else {
    // Manejar errores de consulta
    die("Error en la consulta: " . $conn->error);
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los datos en formato JSON
echo json_encode($datos);
?>
