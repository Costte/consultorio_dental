<?php
include 'conexion.php';

$idUsuario = isset($_POST['id']) ? $_POST['id'] : null;
$nuevoNombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$nuevoApellido_paterno = isset($_POST['apellido_paterno']) ? $_POST['apellido_paterno'] : '';
$nuevoApellido_materno = isset($_POST['apellido_materno']) ? $_POST['apellido_materno'] : '';
$nuevofecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
$nuevoedad = isset($_POST['edad']) ? $_POST['edad'] : '';
$nuevotelefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
$nuevocorreo = isset($_POST['correo']) ? $_POST['correo'] : '';
$nuevosexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
$nuevofecha_ingreso = isset($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : '';
$nuevoalergias = isset($_POST['alergias']) ? $_POST['alergias'] : '';
$nuevodoctor = isset($_POST['id_doctor']) ? $_POST['id_doctor'] : '';
$nuevotratamiento = isset($_POST['id_tratamiento']) ? $_POST['id_tratamiento'] : '';

if ($idUsuario === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de usuario válido.'
    ];
} else {
    $sql = "UPDATE pacientes SET nombre = '$nuevoNombre', apellido_paterno = '$nuevoApellido_paterno', apellido_materno = '$nuevoApellido_materno', fecha_nacimiento = '$nuevofecha_nacimiento', edad = '$nuevoedad', telefono = '$nuevotelefono', correo = '$nuevocorreo', sexo = '$nuevosexo', fecha_ingreso = '$nuevofecha_ingreso', alergias = '$nuevoalergias', id_doctor = '$nuevodoctor', id_tratamiento = '$nuevotratamiento' WHERE id = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        // Actualizar el campo id_tratamiento en la tabla citas
        $sql_update_citas = "UPDATE citas SET id_tratamiento = '$nuevotratamiento' WHERE id_paciente = $idUsuario";

        if ($conn->query($sql_update_citas) === TRUE) {
            $response = [
                'success' => true,
                'message' => 'Paciente actualizado con éxito y tratamientos en citas actualizados.',
                'data' => [
                    'id' => $idUsuario,
                    'nombre' => $nuevoNombre,
                    'apellido_paterno' => $nuevoApellido_paterno,
                ],
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Error al actualizar los tratamientos en citas: ' . $conn->error
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al actualizar el Paciente: ' . $conn->error
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>


