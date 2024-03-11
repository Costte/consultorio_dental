<?php
// Al principio del script PHP
session_start([
    'cookie_lifetime' => 86400, // Tiempo de vida de la cookie en segundos
    'cookie_secure'   => true,   // Solo enviar cookies en conexiones seguras (HTTPS)
    'cookie_httponly' => true,   // Hace que las cookies solo sean accesibles a través de HTTP
    'use_strict_mode' => true,   // Evita que PHP acepte sesiones con identificadores incorrectos
]);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtiene los datos del formulario
$data = json_decode(file_get_contents('php://input'), true);
$username = mysqli_real_escape_string($conn, $data['username']);
$password = mysqli_real_escape_string($conn, $data['password']);

$_SESSION["username"] = $username;
// Verifica la autenticación
$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $response = array('authenticated' => true);
} else {
    $response = array('authenticated' => false);
}


// Devuelve la respuesta como JSON
echo json_encode($response);

$conn->close();
?>
