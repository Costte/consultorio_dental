<?php
// db.php - Este archivo se encarga de la conexión a la base de datos

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
