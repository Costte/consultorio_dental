<?php

function regresarConexion(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "consultorio_dental";

    $conexion = mysqli_connect($servername, $username, $password, $dbname) or die ("Problemas con la conexion");
    mysqli_set_charset($conexion, 'utf8');
    return $conexion;
}


?>