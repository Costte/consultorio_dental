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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">

    <title>Sistema</title>
    <script>
        //Función para mostrar una alerta
        function mostrarAlerta(mensaje) {
            alert(mensaje);
        }

        // Función para limpiar los campos del formulario después de cierto tiempo (en milisegundos)
        function limpiarCamposDespuesDeTiempo(tiempo) {
            setTimeout(function() {
                document.getElementById('formulario_busqueda').reset();
            }, tiempo);
        }
    </script>


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
        $apellido_paterno = $_POST["apellido_paterno"];

        // Consulta SQL para buscar en la tabla de pacientes
        $sql = "SELECT pacientes.*, doctores.nombre as nombre_docto, doctores.apellido_paterno as apellido_doctor,
        tratamientos.nombre as nombre_tratamiento
        FROM pacientes
        LEFT JOIN doctores ON pacientes.Id_doctor = doctores.id
        LEFT JOIN tratamientos ON pacientes.Id_tratamiento = tratamientos.id
        WHERE pacientes.nombre = '$nombre' OR pacientes.apellido_paterno = '$apellido_paterno'";

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

</head>

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

            <div class="containe_paci">

                <div class="Alertas">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Pacientes </h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>

                <div class="formu_1">

                    <div class="int_form">

                        <div class="titulo">Agrega un Paciente</div>
                        <!-- Formulario para agregar pacientes -->
                        <form id="pacienteForm" method="post" class="pacienteForm">


                            <div class="input_group">
                                <label for="nombrePaciente" class="label">Nombre:</label>
                                <input type="text" id="nombrePaciente" name="nombrePaciente" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="apellidoPater" class="label">Primer Apellido:</label>
                                <input type="text" id="apellidoPater" name="apellidoPater" autocomplete="off" class="input">

                            </div>
                            <div class="input_group">
                                <label for="apellidoMater" class="label">Segundo Apellido:</label>
                                <input type="text" id="apellidoMater" name="apellidoMater" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="fechanacimientoPaciente" class="label">Fecha Nacimiento:</label>
                                <input type="date" id="fechanacimientoPaciente" name="fechanacimientoPaciente" class="input">

                            </div>

                            <div class="input_group">
                                <label for="edadPaciente" class="label">Edad:</label>
                                <input type="number" id="edadPaciente" name="edadPaciente" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="telefonoPaciente" class="label">Telefono:</label>
                                <input type="tel" id="telefonoPaciente" name="telefonoPaciente" maxlength="10" pattern="[0-9]{10}" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="emailPaciente" class="label">Correo Electronico:</label>
                                <input type="email" id="emailPaciente" name="emailPaciente" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="sexoPaciente" class="label">Sexo:</label>
                                <select id="sexoPaciente" name="sexoPaciente" aria-label="Floating label select example" required class="input">
                                    <option selected> Selecciona una opcion</option>
                                    <option value="hombre">Hombre</option>
                                    <option value="mujer">Mujer</option>
                                </select>

                            </div>

                            <div class="input_group">
                                <label for="fechaIngresoPaciente" class="label">Fecha Registro:</label>
                                <input type="date" id="fechaIngresoPaciente" name="fechaIngresoPaciente" class="input">

                            </div>

                            <div class="input_group">
                                <label for="alergiasPaciente" class="label">Alergias:</label>
                                <textarea id="alergiasPaciente" name="alergiasPaciente" rows="2" placeholder="" class="input"></textarea>

                            </div>


                            <div>
                                <button type="submit" onclick="mostrarAlerta()">Agregar Paciente</button>
                            </div>

                        </form>


                        <div id="miAlerta" class="modale">

                            <span class="cerrar" onclick="cerrarAlerta()">&times;</span>
                            <p id="mensaje"></p>

                        </div>

                    </div>


                </div>

                <div class="tbl_paci">

                    <h3 class="titulo_bus">Busca un Paciente</h3>
                    <!-- Formulario de búsqueda -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="buscador_form">
                        <div>
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre">
                        </div>
                        <div>
                            <label for="apellido_paterno">Apellido:</label>
                            <input type="text" id="apellido_paterno" name="apellido_paterno">
                        </div>
                        <div><input type="submit" value="Buscar">
                        </div>
                    </form>
                    <button onclick="buscarUsuarios()">Buscar todos los Pacientes</button>
                    <div id="resultados" class="resultados"></div>

                    <?php
                    echo "<div id='miAlerta' class='modal3>";

                    echo "<span class='cerrar2' onclick='cerrarAlerta()'></span>";
                    if (!empty($resultados)) {

                        echo "<h4 class='titulo_bus'>Resultados de la búsqueda:</h4>";
                        echo "<table class='result_general'>";

 
                            echo "<tr>";
                            echo "<th>ID</th><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Fecha de Ingreso</th><th>Historial Medico</th><th>Editar</th><th>Eliminar</th>";
                            echo "</tr>";
                            foreach ($resultados as $row) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["apellido_paterno"] . "</td><td>" . $row["apellido_materno"] . "</td><td>" . $row["fecha_ingreso"] . "</td><td class='action-buttons'><a href='historial_paciente.php?id=" . $row["id"] . "'><button>Abrir</button></a></a></td><td class='action-buttons'><button onclick='editarUsuario(" . $row["id"] . ")'>Editar</button></td><td class='action-buttons'><button onclick='eliminarUsuario(" . $row["id"] . ")'>Eliminar</button></td>";
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






    <script src="paciente.js"></script>
    <script src="script.js"></script>
    <script src="notas.js"></script>

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
        document.getElementById('fechaIngresoPaciente').value = obtenerFechaActual();

        function mostrarAlerta() {
            document.getElementById("miAlerta").style.display = "block";
        }

        function cerrarAlerta() {
            document.getElementById("miAlerta").style.display = "none";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="alerta.js"></script>


    <!-- JavaScript para manejar la lógica de edición y eliminación -->

</body>

</html>