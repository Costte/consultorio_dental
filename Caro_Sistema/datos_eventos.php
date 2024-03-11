<?php
header('Content-Type: application/json');

require("coneccion.php");

$conexion = regresarConexion();

switch ($_GET['accion']) {

    case 'listar':

        $datos = mysqli_query($conexion, " select codigo as id, 
        titulo as title,
        descripcion,
        inicio as start,
        fin as end,
        id_paciente as paciente,
        colorfondo as backgroundColor
        from eventos");
        $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);

        echo json_encode($resultado);


        break;


        case 'agregar':
            // Verificar si todos los campos necesarios están presentes
            if(isset($_POST['titulo'], $_POST['descripcion'], $_POST['inicio'], $_POST['fin'], $_POST['id_paciente'], $_POST['colorfondo'])) {
                // Iniciar transacción
                $conexion->begin_transaction();
                
                // Insertar en la tabla eventos
                $stmt1 = $conexion->prepare("INSERT INTO eventos (titulo, descripcion, inicio, fin, id_paciente, colorfondo) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt1->bind_param("ssssis", $_POST['titulo'], $_POST['descripcion'], $_POST['inicio'], $_POST['fin'], $_POST['id_paciente'], $_POST['colorfondo']);
                $respuesta = $stmt1->execute();
                
                if ($respuesta) {
                    // Obtener el ID recién insertado
                    $id_evento = $stmt1->insert_id;
                    
                    // Insertar en la tabla citas usando el ID de eventos
                    $stmt2 = $conexion->prepare("INSERT INTO citas (descripcion_cita, fecha, id_paciente, codigo_eventos) VALUES (?, ?, ?, ?)");
                    $stmt2->bind_param("ssii", $_POST['titulo'], $_POST['inicio'], $_POST['id_paciente'], $id_evento);
                    $respuesta2 = $stmt2->execute();
                    
                    if ($respuesta2) {
                        $conexion->commit();
                        echo json_encode('Inserción exitosa en ambas tablas.');
                    } else {
                        $conexion->rollback();
                        echo json_encode("Error en la inserción en la tabla citas.");
                    }
                } else {
                    $conexion->rollback();
                    echo json_encode("Error en la inserción en la tabla eventos.");
                }
                
                // Cerrar las consultas preparadas
                $stmt1->close();
                $stmt2->close();
            } else {
                echo json_encode("Faltan campos necesarios.");
            }
            break;
        

            case 'modificar':
                // Verificar si todos los campos necesarios están presentes
                if(isset($_POST['codigo'], $_POST['titulo'], $_POST['descripcion'], $_POST['inicio'], $_POST['fin'], $_POST['id_paciente'], $_POST['colorfondo'])) {
                    // Preparar las consultas SQL para evitar inyección SQL
                    $stmt1 = $conexion->prepare("UPDATE eventos SET titulo = ?, descripcion = ?, inicio = ?, fin = ?, id_paciente = ?, colorfondo = ? WHERE codigo = ?");
                    $stmt2 = $conexion->prepare("UPDATE citas SET descripcion_cita = ?, fecha = ?, id_paciente = ? WHERE codigo_eventos = ?");
            
                    // Unir los valores a las consultas preparadas
                    $stmt1->bind_param("ssssisi", $_POST['titulo'], $_POST['descripcion'], $_POST['inicio'], $_POST['fin'], $_POST['id_paciente'], $_POST['colorfondo'], $_POST['codigo']);
                    $stmt2->bind_param("ssii", $_POST['titulo'], $_POST['inicio'], $_POST['id_paciente'], $id_evento);
            
                    // Iniciar transacción
                    $conexion->begin_transaction();
            
                    // Ejecutar la primera consulta
                    if ($stmt1->execute()) {
                        $id_evento = $_POST['codigo']; // Usar el código del evento para la segunda consulta
                        // Ejecutar la segunda consulta
                        if ($stmt2->execute()) {
                            $conexion->commit();
                            echo json_encode('Actualización exitosa en ambas tablas.');
                        } else {
                            $conexion->rollback();
                            echo json_encode("Error en la actualización en la tabla citas.");
                        }
                    } else {
                        $conexion->rollback();
                        echo json_encode("Error en la actualización en la tabla eventos.");
                    }
            
                    // Cerrar las consultas preparadas
                    $stmt1->close();
                    $stmt2->close();
                } else {
                    echo json_encode("Faltan campos necesarios.");
                }
                break;
            

                case 'eliminar':
                    // Verificar si el código del evento está presente
                    if(isset($_POST['codigo'])) {
                        // Iniciar transacción
                        $conexion->begin_transaction();
                
                        // Eliminar el evento de la tabla 'eventos'
                        $stmt1 = $conexion->prepare("DELETE FROM eventos WHERE codigo = ?");
                        $stmt1->bind_param("i", $_POST['codigo']);
                
                        // Actualizar el campo 'estatus_cita' en la tabla 'citas'
                        $stmt2 = $conexion->prepare("UPDATE citas SET estatus_cita = 'Cancelada' WHERE codigo_eventos = ?");
                        $stmt2->bind_param("i", $_POST['codigo']);
                
                        // Ejecutar la primera consulta
                        $respueste1 = $stmt1->execute();
                
                        // Ejecutar la segunda consulta
                        $respueste2 = $stmt2->execute();
                
                        // Comprobar si ambas consultas se ejecutaron con éxito
                        if ($respueste1 && $respueste2) {
                            $conexion->commit();
                            echo json_encode('Eliminación exitosa y actualización en la tabla citas.');
                        } else {
                            $conexion->rollback();
                            echo json_encode("Error en la eliminación o actualización de datos.");
                        }
                
                        // Cerrar las consultas preparadas
                        $stmt1->close();
                        $stmt2->close();
                    } else {
                        echo json_encode("Falta el código del evento.");
                    }
                    break;
                
}
