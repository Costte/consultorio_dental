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
$nombreinve = $_POST['nombreinve'];
$cantidad = $_POST['cantidad'];
$costo = $_POST['costo'];
$cantida_min = $_POST['cantida_min'];
$categoria = $_POST['categoria'];
$valor_inve = $costo * $cantidad;
$fechaIngresoproducto = $_POST['fecha_actualizado'];




// Consulta SQL para verificar si el usuario ya existe
$sql = "SELECT * FROM inventario WHERE nombre = '$nombreinve'";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // El usuario ya existe
    echo "El producto ya fue agregado anteriormente.";
} else{

// Insertar datos en la base de datos
$sql = "INSERT INTO inventario (nombre, cantidad, costo_unitario, valor_inventario, cantidad_minima, categoria, fecha_actualizado) 
VALUES ('$nombreinve', '$cantidad', '$costo', '$valor_inve', '$cantida_min', '$categoria', '$fechaIngresoproducto')";


if ($conn->query($sql) === TRUE) {
    echo "Producto agregado con éxito.";
} else {
    echo "Error al agregar al producto: " . $conn->error;
}
}
// Cerrar conexión
$conn->close();
?>
