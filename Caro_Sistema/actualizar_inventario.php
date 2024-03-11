<?php
// actualizar_usuario.php - Este archivo maneja la actualización de un usuario en la base de datos

include 'conexion.php';

// Obtener los datos del usuario desde la solicitud POST
$idInven = isset($_POST['id']) ? $_POST['id'] : null;
$nuevoNombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$nuevocantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : '';
$nuevocosto_unidad = isset($_POST['costo_unitario']) ? $_POST['costo_unitario'] : '';
$nuevovalor_inventario = isset($_POST['valor_inventario']) ? $_POST['valor_inventario'] : '';
$nuevocantidad_minima = isset($_POST['cantidad_minima']) ? $_POST['cantidad_minima'] : '';
$nuevocategoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
$nuevofecha = isset($_POST['fecha_actualizado']) ? $_POST['fecha_actualizado'] : '';
$fecha_actual = date("Y-m-d");

// Validar que se proporcionó un ID de usuario válido
if ($idInven === null) {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un ID de producto válido.'
    ];
} else {
    // Obtener el valor anterior de valor_inventario desde la base de datos
    $sql_select = "SELECT valor_inventario FROM inventario WHERE id = $idInven";
    $result_select = $conn->query($sql_select);
    $row_select = $result_select->fetch_assoc();
    $valor_inventario_anterior = $row_select['valor_inventario'];

    // Actualizar el registro
    $sql = "UPDATE inventario SET nombre = '$nuevoNombre', cantidad = '$nuevocantidad', costo_unitario = '$nuevocosto_unidad', valor_inventario = '$nuevovalor_inventario', cantidad_minima = '$nuevocantidad_minima', categoria = '$nuevocategoria' , fecha_actualizado = '$fecha_actual' WHERE id = $idInven";

    if ($conn->query($sql) === TRUE) {
        // Calcular la diferencia entre el valor anterior y el nuevo valor de valor_inventario
        $diferencia = $nuevovalor_inventario - $valor_inventario_anterior;

        // Si hay un incremento en valor_inventario, insertar en la tabla utilidades
        if ($diferencia > 0) {
            $sql_insert = "INSERT INTO utilidades (tipo, fecha, descripcion, id_cita, id_paciente, id_inventario, nombre_inventario, monto) VALUES ('Gasto', NOW(), 'Compra de Material', '0', '0', $idInven, '$nuevoNombre', $nuevovalor_inventario)";
            if ($conn->query($sql_insert) === TRUE) {
                $response = [
                    'success' => true,
                    'message' => 'Producto actualizado con éxito y registro de utilidad insertado.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Error al insertar registro de utilidad: ' . $conn->error
                ];
            }
        } else {
            $response = [
                'success' => true,
                'message' => 'Producto actualizado con éxito.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al actualizar el producto: ' . $conn->error
        ];
    }
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

?>
