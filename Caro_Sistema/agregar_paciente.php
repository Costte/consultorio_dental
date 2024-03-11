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
$nombrePaciente = $_POST['nombrePaciente'];
$apellidoPater = $_POST['apellidoPater'];
$apellidoMater = $_POST['apellidoMater'];
$fechanacimientoPaciente = $_POST['fechanacimientoPaciente'];
$edadPaciente = $_POST['edadPaciente'];
$telefonoPaciente = $_POST['telefonoPaciente'];
$emailPaciente = $_POST['emailPaciente'];
$sexoPaciente = $_POST['sexoPaciente'];
$fechaIngresoPaciente = $_POST['fechaIngresoPaciente'];
$alergiasPaciente = $_POST['alergiasPaciente'];


// Consulta SQL para verificar si el usuario ya existe
$sql = "SELECT * FROM pacientes WHERE nombre = '$nombrePaciente' AND apellido_paterno = '$apellidoPater' AND apellido_materno = '$apellidoMater'";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // El usuario ya existe
    echo "El paciente ya fue agregado anteriormente.";
} else{

// Insertar datos en la base de datos
$sql = "INSERT INTO pacientes (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, edad, telefono, correo, sexo, fecha_ingreso, alergias, id_doctor, id_tratamiento) 
VALUES ('$nombrePaciente', '$apellidoPater', '$apellidoMater', '$fechanacimientoPaciente', '$edadPaciente', '$telefonoPaciente', '$emailPaciente', '$sexoPaciente', '$fechaIngresoPaciente', '$alergiasPaciente', DEFAULT, DEFAULT)";


if ($conn->query($sql) === TRUE) {
    echo "Paciente agregado con éxito.";
} else {
    echo "Error al agregar al paciente: " . $conn->error;
}
}
// Cerrar conexión
$conn->close();
?>
