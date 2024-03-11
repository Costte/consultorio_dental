<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Indicar al navegador que no almacene en caché la respuesta
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado

// Tu lógica para buscar citas y devolver la respuesta en formato JSON

// Obtener el ID de la cita enviado por AJAX
$bus_cita = isset($_POST['bus_cita']) ? $_POST['bus_cita'] : '';

// Consulta SQL para obtener la información de la cita
$sql = "SELECT u.id,u.tipo, u.fecha, u.descripcion, 
u.id_cita, u.id_inventario, 
u.nombre_inventario, u.monto, u.id_paciente, p.nombre AS nombre_paciente FROM utilidades u INNER JOIN pacientes p ON u.id_paciente = p.id WHERE u.id_cita = $bus_cita";

$result = $conn->query($sql);

// Crear un array con los resultados
$usersd = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usersd[] = $row;
    }
}

// Cerrar la conexión a la base de datos
$conn->close();

// Devolver los resultados como JSON
echo json_encode($usersd);

?>
