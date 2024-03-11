<?php
// actualizar_usuario.php - Este archivo maneja la actualización de un usuario en la base de datos

include 'conexion.php';

// Obtener los datos del usuario desde la solicitud POST
$idCita = isset($_POST['id']) ? $_POST['id'] : null;
$nuevodescripcion_cita = isset($_POST['descripcion_cita']) ? $_POST['descripcion_cita'] : '';
$nuevofecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$nuevoid_paciente = isset($_POST['id_paciente']) ? $_POST['id_paciente'] : '';
$nuevoid_tratamiento = isset($_POST['id_tratamiento']) ? $_POST['id_tratamiento'] : '';
$nuevocosto_tratamiento = isset($_POST['costo_tratamiento']) ? $_POST['costo_tratamiento'] : '';
$nuevoconsulta_costo = isset($_POST['consulta_costo']) ? $_POST['consulta_costo'] : '';
$nuevocosto_total= isset($_POST['costo_total']) ? $_POST['costo_total'] : '';
$nuevocosto_pagado = isset($_POST['costo_pagado']) ? $_POST['costo_pagado'] : '';
$nuevototal_deuda = isset($_POST['total_deuda']) ? $_POST['total_deuda'] : '';
$nuevoestatus_deuda = isset($_POST['estatus_deuda']) ? $_POST['estatus_deuda'] : '';
$nuevoestatus_cita = isset($_POST['estatus_cita']) ? $_POST['estatus_cita'] : '';
$nuevofecha_pago = isset($_POST['fecha_pago']) ? $_POST['fecha_pago'] : '';
$nuevocodigo_eventos = isset($_POST['codigo_eventos']) ? $_POST['codigo_eventos'] : '';

// Validar que se proporcionó un ID de usuario válido
if ($idCita === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de Cita válido.'
    ];
} else {
    // Iniciar transacción
    $conn->begin_transaction();

    // Obtener el costo pagado anterior
    $sql_costo_anterior = "SELECT costo_pagado FROM citas WHERE id = $idCita";
    $result = $conn->query($sql_costo_anterior);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $costo_pagado_anterior = $row['costo_pagado'];
    } else {
        $costo_pagado_anterior = 0;
    }

    // Actualizar la cita
    $sql = "UPDATE citas SET id_tratamiento = '$nuevoid_tratamiento', costo_tratamiento = '$nuevocosto_tratamiento', consulta_costo = '$nuevoconsulta_costo', costo_total = '$nuevocosto_total', costo_pagado = '$nuevocosto_pagado', total_deuda = '$nuevototal_deuda', estatus_deuda = '$nuevoestatus_deuda', estatus_cita = '$nuevoestatus_cita', fecha_pago = '$nuevofecha_pago' WHERE id = $idCita";

    if ($conn->query($sql) === TRUE) {
        $response['cita_updated'] = true;
    } else {
        $response['cita_updated'] = false;
        $response['cita_error'] = $conn->error;
    }

    // Actualizar el tratamiento del paciente
    $sql_paciente = "UPDATE pacientes SET id_tratamiento = '$nuevoid_tratamiento' WHERE id = '$nuevoid_paciente'";
    if ($conn->query($sql_paciente) === TRUE) {
        $response['paciente_updated'] = true;
    } else {
        $response['paciente_updated'] = false;
        $response['paciente_error'] = $conn->error;
    }

    // Calcular la diferencia de costo pagado
    $diferencia_costo_pagado = $nuevocosto_pagado - $costo_pagado_anterior;

    // Insertar en la tabla de utilidades
    $sql_utilidades = "INSERT INTO utilidades (tipo, fecha, descripcion, id_cita, id_paciente, id_inventario, nombre_inventario, monto) VALUES ('Ingreso', NOW(), '$nuevodescripcion_cita', '$idCita', '$nuevoid_paciente', '0', 'No Aplica', '$diferencia_costo_pagado')";
    if ($conn->query($sql_utilidades) === TRUE) {
        $response['utilidades_inserted'] = true;
    } else {
        $response['utilidades_inserted'] = false;
        $response['utilidades_error'] = $conn->error;
    }

    // Confirmar o revertir la transacción
    if ($response['cita_updated'] && $response['paciente_updated'] && $response['utilidades_inserted']) {
        $conn->commit(); // Confirmar la transacción
        $response['success'] = true;
        $response['message'] = 'Todas las operaciones se completaron con éxito.';
    } else {
        $conn->rollback(); // Revertir la transacción
        $response['success'] = false;
        $response['message'] = 'Hubo errores al procesar las operaciones.';
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>

