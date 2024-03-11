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
        $nombre = $_POST["nombre"];

        // Consulta SQL para buscar en la tabla de pacientes
        $sql = "SELECT * FROM inventario
        WHERE inventario.nombre = '$nombre'";

        $result = $conn->query($sql);

        // Almacenar los resultados en un array
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }

        // Mensaje si no se encontraron resultados
        if (empty($resultados)) {
            $mensaje = "No se encontraron resultados.";
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

        <div class="container_doc">
                <div class="alertas_doc">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Inventario</h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>

                <div class="form_doct">
                    <div class="doc_form">

                        <div class="titulo">Agrega un producto</div>
                        <!-- Formulario para agregar doctores -->
                        <form id="trataForm" method="post" class="doctorForm">


                            <div class="input_group">
                                <label for="nombreinve" class="label">Nombre:</label>
                                <input type="text" id="nombreinve" name="nombreinve" autocomplete="off" class="input">
                            </div>

                            <div class="input_group">
                                <label for="cantidad" class="label">Cantidad:</label>
                                <input type="number" id="cantidad" name="cantidad" autocomplete="off" class="input">
                            </div>
                            
                            <div class="input_group">
                                <label for="costo" class="label">Costo:</label>
                                <input type="text" id="costo" name="costo" autocomplete="off" class="input">
                            </div>


                            <div class="input_group">
                                <label for="cantida_min" class="label">Cantidad Minima:</label>
                                <input type="text" id="cantida_min" name="cantida_min" autocomplete="off" class="input">
                            </div>

                            <div class="input_group">
                            <label for="categoria" class="label">Categoria:</label>
                                <select id="categoria" name="categoria" aria-label="Floating label select example" required class="input">
                                    <option selected> Selecciona una opcion</option>
                                    <option value="Instrumentos y Herramientas">Instrumentos y Herramientas</option>
                                    <option value="Materiales dentales">Materiales dentales</option>
                                    <option value="Equipos y aparatos">Equipos y aparatos</option>
                                    <option value="Suministros de oficina<">Suministros de oficina</option>
                                    <option value="Suministros de higiene y limpieza">Suministros de higiene y limpieza</option>
                                </select>
                            </div>
                            <div class="input_group">
                                <label for="fechaIngresoproducto" class="label">Fecha Registro:</label>
                                <input type="date" id="fechaIngresoproducto" name="fechaIngresoproducto" class="input">

                            </div>

                            <div>
                                <button type="submit" onclick="mostrarAlerta()">Agregar a Inventario</button>
                            </div>

                            <div id="miAlerta2" class="modal2">

                                <span class="cerrar" onclick="cerrarAlerta()">&times;</span>
                                <p id="mensaje" class="mensajes"></p>

                            </div>

                        </form>

                    </div>
                </div>


                <div class="result_doct" style="align-items: center;">

                <h3 class="titulo_bus">Busca un Producto</h3>
                    <!-- Formulario de búsqueda -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="buscador_form" style="display: flex;">
                        <div>
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre">
                        </div>
                        <div><input type="submit" value="Buscar">
                        </div>

                        
                    </form>
                    <div class="buscador_form">
                        <button onclick="buscarInventario()">Buscar todo el Inventario</button>
                    </div>



                    <div id="resultados_inv" class="resultados_inv"></div>


                    <?php
                    echo "<div id='miAlerta' class='modal3>";

                    echo "<span class='cerrar2' onclick='cerrarAlerta()'></span>";
                    if (!empty($resultados)) {

                        echo "<h4 class='titulo_bus'>Resultados de la búsqueda:</h4>";
                        echo "<table class='result_general'>";

 
                            echo "<tr>";
                            echo "<th>ID</th> <th>Nombre</th> <th>Cantidad</th> <th>Cantidad Mininma</th> <th>Categoria</th> <th>Editar</th> <th>Eliminar</th>";
                            echo "</tr>";
                            foreach ($resultados as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td> <td>" . $row["nombre"] . "</td> <td>" . $row["cantidad"] . "</td> <td>" . $row["cantidad_minima"] . "</td> <td>" . $row["categoria"] . "</td> <td class='action-buttons'><button onclick='editarInventario(" . $row["id"] . ")'>Editar</button></td><td class='action-buttons'><button onclick='eliminarInventario(" . $row["id"] . ")'>Eliminar</button></td>";
                            echo "</tr>";

                        }

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
    <script>
     // Obtener la fecha actual en el formato YYYY-MM-DD
     function obtenerFechaActual() {
            const hoy = new Date();
            const anio = hoy.getFullYear();
            let mes = hoy.getMonth() + 1;
            let dia = hoy.getDate();

            // Ajustar el formato para tener dos dígitos en mes y día
            mes = mes < 10 ? '0' + mes : mes;
            dia = dia < 10 ? '0' + dia : dia;

            return `${anio}-${mes}-${dia}`;
        }

        // Establecer la fecha actual en el campo de entrada al cargar la página
        document.getElementById('fechaIngresoproducto').value = obtenerFechaActual();

        function mostrarAlerta() {
            document.getElementById("miAlerta2").style.display = "block";
        }

        function cerrarAlerta() {
            document.getElementById("miAlerta2").style.display = "none";
        }
    </script>
    <script src="notas.js"></script>
    <script src="alerta.js"></script>
    <script src="inventario.js"></script>
    <script src="buscar_inven.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof cerrarModal !== "undefined" && cerrarModal) {
            $('#myModal').modal('hide');
        }
    });
</script>

    
</body>

</html>