<?php
// Aquí deberías configurar la conexión a tu base de datos
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

// Obtener datos del formulario
$nombreDoctor = $_POST['nombreDoctor'];
$apellidoPaterDoc = $_POST['apellidoPaterDoc'];
$apellidoMaterDoc = $_POST['apellidoMaterDoc'];
$cedula = $_POST['cedula'];
$especialidad = $_POST['especialidad'];
$fechaIngresoDoctor = $_POST['fechaIngresoDoctor'];



// Consulta SQL para verificar si el usuario ya existe
$sql = "SELECT * FROM doctores WHERE nombre = '$nombreDoctor' AND apellido_paterno = '$apellidoPaterDoc' AND apellido_materno = '$apellidoMaterDoc'";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // El usuario ya existe
    echo "El Doctor ya fue agregado anteriormente.";
} else{

// Insertar datos en la base de datos
$sql = "INSERT INTO doctores (nombre, apellido_paterno, apellido_materno, cedula, especialidad, fecha_ingreso) 
VALUES ('$nombreDoctor', '$apellidoPaterDoc', '$apellidoMaterDoc', '$cedula', '$especialidad', '$fechaIngresoDoctor')";


if ($conn->query($sql) === TRUE) {
    echo "Doctor agregado con éxito.";
} else {
    echo "Error al agregar al Doctor: " . $conn->error;
}
}
// Cerrar conexión
$conn->close();
?>
