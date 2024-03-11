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
            <div class="container_doc">
                <div class="alertas_doc">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Doctores</h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>

                <div class="form_doct">
                    <div class="doc_form">

                        <h3 class="titulo">Agrega un Doctor</h3>
                        <!-- Formulario para agregar doctores -->
                        <form id="doctorForm" method="post" class="doctorForm">


                            <div class="input_group">
                                <label for="nombreDoctor" class="label">Nombre:</label>
                                <input type="text" id="nombreDoctor" name="nombreDoctor" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="apellidoPaterDoc" class="label">Primer Apellido:</label>
                                <input type="text" id="apellidoPaterDoc" name="apellidoPaterDoc" autocomplete="off" class="input">

                            </div>
                            <div class="input_group">
                                <label for="apellidoMaterDoc" class="label">Segundo Apellido:</label>
                                <input type="text" id="apellidoMaterDoc" name="apellidoMaterDoc" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="cedula" class="label">Cedula :</label>
                                <input type="text" id="cedula" name="cedula" autocomplete="off" maxlength="8" class="input">

                            </div>

                            <div class="input_group">
                                <label for="especialidad" class="label">Especialidad:</label>
                                <input type="text" id="especialidad" name="especialidad" autocomplete="off" class="input">

                            </div>

                            <div class="input_group">
                                <label for="fechaIngresoDoctor" class="label">Fecha Registro:</label>
                                <input type="date" id="fechaIngresoDoctor" name="fechaIngresoDoctor" class="input">

                            </div>

                            <div>
                                <button type="submit" onclick="mostrarAlerta()">Agregar Doctor</button>
                            </div>

                            <div id="miAlerta2" class="modal2">

                                <span class="cerrar" onclick="cerrarAlerta()">&times;</span>
                                <p id="mensaje" class="mensajes"></p>

                            </div>

                        </form>

                    </div>
                </div>


                <div class="result_doct">

                    <div class="buscador_form">
                        <h3 class="titulo_bus">Doctores</h3>
                        <button onclick="buscarDoctores()">Buscar todos los Doctores</button>
                    </div>

                    <div id="resultados_doc" class="result_general"></div>


                </div>
            </div>


        </div>
    </section>

    <script src="doctores.js"></script>
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
        document.getElementById('fechaIngresoDoctor').value = obtenerFechaActual();

        function mostrarAlerta() {
            document.getElementById("miAlerta2").style.display = "block";
        }

        function cerrarAlerta() {
            document.getElementById("miAlerta2").style.display = "none";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="alerta.js"></script>
    <script src="buscar_doc.js"></script>
</body>

</html>