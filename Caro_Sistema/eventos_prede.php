<?php
header('Content-Type: application/json');

require("coneccion.php");

$conexion = regresarConexion();

switch ($_GET['accion']){
    case 'listar':
        $datos = mysqli_query($conexion, "select id, titulo, horaini, horafin, colortexto, colorfin from eventos_predefinidos");
        $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
        echo json_encode($resultado);
        break;

    case 'agregar':
        $respuesta = mysqli_query($conexion, "insert into eventos_predefinidos (titulo, horaini, horafin, colortexto, colorfin) 
                                    values ('$_POST[titulo]', '$_POST[horaini]', '$_POST[horafin]', '$_POST[colortexto]','$_POST[colorfin]')");
        echo json_encode($respuesta);
        break;

    case 'borrar':
        $respuesta = mysqli_query($conexion, "delete from eventos_predefinidos where id = '$_POST[id]'");
        echo json_encode($respuesta);
        break;
}
