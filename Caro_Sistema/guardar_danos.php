<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "consultorio_dental";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa"; // Mensaje de éxito si la conexión se establece correctamente
}

// Obtener los datos del POST
$numero_diente = $_POST['numero_diente'];
$seccion = $_POST['seccion'];
$tipo_dano = $_POST['tipo_dano'];
$numero_sec = $_POST['numero_sec'];
$id_paciente = $_POST['id_paciente'];

// Verificar si ya existe una entrada con los mismos valores de numero_diente, seccion e id_paciente
$sql_check = "SELECT * FROM danos_dientes WHERE numero_diente = ? AND seccion = ? AND numero_sec = ? AND id_paciente = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("isss", $numero_diente, $seccion, $numero_sec, $id_paciente);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

// Si ya existe una entrada, actualiza el tipo_dano y la fecha_dano
if ($result_check->num_rows > 0) {
    $sql_update = "UPDATE danos_dientes SET tipo_dano = ?, fecha_dano = NOW() WHERE numero_diente = ? AND seccion = ? AND id_paciente = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("siss", $tipo_dano, $numero_diente, $seccion, $id_paciente);
    if ($stmt_update->execute()) {
        echo "Datos actualizados correctamente";
    } else {
        echo "Error al actualizar los datos: " . $stmt_update->error;
    }
} else {
    // Si no existe una entrada, inserta una nueva fila
    $sql_insert = "INSERT INTO danos_dientes (numero_diente, seccion, numero_sec, tipo_dano, id_paciente, fecha_dano) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("issss", $numero_diente, $seccion, $numero_sec, $tipo_dano, $id_paciente);
    if ($stmt_insert->execute()) {
        echo "Datos insertados correctamente";
    } else {
        echo "Error al insertar los datos: " . $stmt_insert->error;
    }
}

// Cerrar las consultas y la conexión
$stmt_check->close();
$stmt_update->close();
$stmt_insert->close();
$conn->close();
?>
