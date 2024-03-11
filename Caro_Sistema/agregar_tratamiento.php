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
$nombretrata = $_POST['nombretrata'];
$preciotrata = $_POST['preciotrata'];




// Consulta SQL para verificar si el usuario ya existe
$sql = "SELECT * FROM tratamientos WHERE nombre = '$nombretrata'";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // El usuario ya existe
    echo "El tratamiento ya fue agregado anteriormente.";
} else{

// Insertar datos en la base de datos
$sql = "INSERT INTO tratamientos (nombre, costo_tratamiento) 
VALUES ('$nombretrata', '$preciotrata')";


if ($conn->query($sql) === TRUE) {
    echo "Tratamiento agregado con éxito.";
} else {
    echo "Error al agregar al tratamiento: " . $conn->error;
}
}
// Cerrar conexión
$conn->close();
?>
