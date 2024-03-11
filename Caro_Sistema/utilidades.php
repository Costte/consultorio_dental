<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {

    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylus.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Sistema</title>
</head>
<?php
// Conexión a la base de datos (debes proporcionar tus propios detalles de conexión)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "consultorio_dental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar variables para almacenar resultados y mensajes
$resultados = array();
$mensaje = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre y apellido desde el formulario
    $fecha_inicial = $_POST["fecha_inicial"];
    $fecha_final = $_POST["fecha_final"];
    $tipo = $_POST["tipo"];

    // Verificar si se han proporcionado los datos necesarios
    if (empty($fecha_inicial) || empty($fecha_final)) {
        $mensaje = "Favor de ingresar los datos necesarios para la búsqueda.";
    } else {
        // Consulta SQL para buscar en la tabla de utilidades
        $sql = "SELECT utilidades.*, pacientes.nombre as nombre_paci, pacientes.apellido_paterno as apellidop_paci, pacientes.apellido_materno as apellidom_paci 
        FROM utilidades 
        LEFT JOIN pacientes ON utilidades.id_paciente = pacientes.id
        WHERE fecha BETWEEN '$fecha_inicial' AND '$fecha_final'";
        // Si se especifica un tipo, agregarlo a la consulta
        if (!empty($tipo)) {
            $sql .= " AND tipo = '$tipo'";
        }
        $result = $conn->query($sql);

        // Almacenar los resultados en un array
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }

        // Mensaje si no se encontraron resultados
        if (empty($resultados)) {
            $mensaje = "No se encontraron resultados.";
        }
    }

    // Cerrar la conexión
    $conn->close();
}
?>


<body>
    <div class="barralateral close lateral">

        <div class="cabecera">
            <div class="textos logo ">
                <span class="nombres">Small Smile</span>
                <span class="doctor">Doctora Carito</span>
            </div>

            <i class="bx bx-menu toggles"></i>
        </div>

        <div class="menu-bar">
            <div class="menur">


                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="index.php"> <i class="bx bx-home-alt icon"></i>
                            <span class="textos nav-text">Home</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="pacientes.php"> <i class='bx bx-male-female icon'></i>
                            <span class="textos nav-text">Pacientes</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="citas.php"> <i class='bx bx-notepad icon'></i>
                            <span class="textos nav-text">Citas</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="inventario.php"> <i class='bx bx-cart-add icon'></i>
                            <span class="textos nav-text">Inventario</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="doctores.php"> <i class='bx bx-female icon'></i>
                            <span class="textos nav-text">Doctores</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="tratamientos.php"> <i class='bx bx-injection icon'></i>
                            <span class="textos nav-text">Tratamientos</span></a>
                    </li>

                    <li class="nav-link">
                        <a href="utilidades.php"> <i class='bx bx-money-withdraw icon'></i>
                            <span class="textos nav-text">Utilidades</span></a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="logout.php">
                        <i class="bx bx-log-out icon"></i>
                        <span class="textos nav-text">Cerrar sesion</span>
                    </a>
                </li>
            </div>
        </div>

    </div>

    <section class="home">
        <div class="textos">

            <div class="contain_uti">
                <div class="Alertas">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Utilidades</h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>

                <div class="Ingresos">
                    <h4 class="titulo_bus">Ingresos Recientes</h4>
                    <div id="resultados_ingresos" class="result_general"></div>
                </div>


                <div class="Egresos">
                    <h4 class="titulo_bus">Gastos Recientes</h4>
                    <div id="resultados_gastos" class="result_general"></div>
                </div>


                <div class="Busqueda_gastos">

                    <h3 class="titulo_bus">Busca los ingresos y egresos</h3>
                    <!-- Formulario de búsqueda -->
                    <form class="buscador_form_gin " method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                        <div class="input_group">
                            <label for="" class="label">Fecha inicial:</label>
                            <input type="date" name="fecha_inicial" class="input">
                        </div>

                        <div class="input_group">
                            <label for="" class="label">Fecha final:</label>
                            <input type="date" name="fecha_final" class="input">
                        </div>
                        <div class="input_group">
                            <label for="" class="label">Tipo:</label>
                            <select name="tipo" class="input">
                                <option value="">Seleccionar tipo</option>
                                <option value="Gasto">Gasto</option>
                                <option value="Ingreso">Ingreso</option>
                                <!-- Agrega más opciones según sea necesario -->
                            </select>
                        </div>
                        <div> <button type="submit" value="Buscar">Buscar</button></div>

                    </form>

                    <div class="busquedas_gen">

                        <div class="busqueda_gen_cita">
                            <h5>Buscar por Cita</h5>
                            <form id="formBuscar">
                                <div class="input_group">
                                    <label for="bus_cita" class="label">ID de la Cita:</label>
                                    <input type="text" id="bus_cita" name="bus_cita" class="input">
                                </div>
                                <div class="input_group">
                                    <button type="submit">Buscar</button>
                                </div>
                            </form>
                        </div>

                        <div class="busqueda_gen_paciente">
                            <h5>Buscar por Paciente</h5>
                            <form id="formBuscar2">
                                <div class="input_group">
                                    <label for="bus_paci" class="label">Paciente:</label>
                                    <input type="text" id="bus_paci" name="bus_paci" class="input">
                                </div>
                                <div class="input_group">
                                    <button onclick="buscarPaciUtil()">Buscar</button>
                                </div>
                            </form>
                        </div>

                        <div class="busqueda_gen_producto">
                            <h5>Buscar por Producto</h5>
                            <form id="formBuscar22">
                                <div class="input_group">
                                    <label for="bus_inve" class="label">Producto:</label>
                                    <input type="text" id="bus_inve" name="bus_inve" class="input">
                                </div>
                                <div class="input_group">
                                    <button onclick="buscarInveUtil()">Buscar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div id="resultados_gen"></div>


                    <?php
                    echo "<div id='miAlerta' class='modal3>";

                    echo "<span class='cerrar2' onclick='cerrarAlerta()'></span>";
                    if (!empty($resultados)) {

                        echo "<h4 class='titulo_bus'>Resultados de la búsqueda:</h4>";
                        echo "<table class='result_general'>";
                        echo "<tr>";
                        echo "<th>ID</th><th>Tipo</th><th>Fecha</th><th>Descripción</th><th>ID Cita</th><th>Paciente</th><th>ID Inventario</th><th>Nombre Producto</th><th>Monto</th>";
                        echo "</tr>";

                        $total_monto = 0; // Inicializamos la variable para calcular el total del monto

                        foreach ($resultados as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td><td";

                            // Aplicar estilo condicional al tipo de transacción
                            if ($row["tipo"] == "Ingreso") {
                                echo " style='background-color: PaleGreen; color: white;'";
                            } elseif ($row["tipo"] == "Gasto") {
                                echo " style='background-color: red; color: white;'";
                            }

                            echo ">" . $row["tipo"] . "</td><td>" . $row["fecha"] . "</td><td>" . $row["descripcion"] . "</td><td>" . $row["id_cita"] . "</td><td>" . $row["nombre_paci"] . " " . $row["apellidop_paci"] . " " . $row["apellidom_paci"] . "</td><td>" . $row["id_inventario"] . "</td><td>" . $row["nombre_inventario"] . "</td><td>" . $row["monto"] . "</td>";
                            echo "</tr>";


                            // Sumamos el monto de cada fila al total
                            $total_monto += $row["monto"];
                        }

                        echo "<tr>";
                        echo "<td colspan='8'>Total:</td><td>" . $total_monto . "</td>"; // Mostramos la fila con el total
                        echo "</tr>";

                        echo "</table>";
                    } else {
                        // Mostrar mensaje si no hay resultados
                        echo "<p>$mensaje</p>";
                    }
                    echo "</div>";
                    ?>

                </div>
            </div>


        </div>
    </section>

    <script src="script.js"></script>
    <script src="notas.js"></script>
    <script src="busqueda_general_tabla2.js"></script>
    <script src="utilidades_buscar_cita.js"></script>
    <script src="utilidades_buscar_paci.js"></script>
    <script src="utilidades_buscar_inven.js"></script>

</body>

</html>