<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener el ID del tratamiento desde la solicitud GET
$idTratamiento = $_GET['id'];

// Consulta SQL para obtener el costo del tratamiento
$consulta = "SELECT costo_tratamiento FROM tratamientos WHERE id = $idTratamiento";
$resultado = $conexion->query($consulta);

// Verificar si se encontró el tratamiento
if ($resultado->num_rows > 0) {
    // Obtener el costo del tratamiento
    $fila = $resultado->fetch_assoc();
    $costoTratamiento = $fila["costo_tratamiento"];
    
    // Devolver el costo del tratamiento como respuesta
    echo $costoTratamiento;
} else {
    // Si no se encuentra el tratamiento, devolver un mensaje de error
    echo "Tratamiento no encontrado";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>