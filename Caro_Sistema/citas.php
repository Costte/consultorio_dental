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
    <link rel="stylesheet" href="stylus_calendario.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!---Links CSS-->
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="fullcalendar/main.css">
    <!---Fin link CSS-->

    <!---Links JS-->
    <script src="js/jquery-3.6.3.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/bootstrap-clockpicker.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    <script src="fullcalendar/main.js"></script>
    <!---FIN Links JS-->


    <title>Sistema</title>


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
    $nombre_tratamiento = $_POST["nombre"];
    

    // Consulta SQL para buscar en la tabla de pacientes
    $sql = "SELECT citas.*, pacientes.nombre as nombre_paciente, pacientes.apellido_paterno as apellido_paciente,
            tratamientos.nombre as nombre_tratamiento
            FROM citas
            LEFT JOIN pacientes ON citas.id_paciente = pacientes.id
            LEFT JOIN tratamientos ON cita.id_tratamiento = tratamientos.id
            WHERE pacientes.nombre = '$nombre' AND pacientes.apellido_paterno = '$apellido_paterno'";
            
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


            <div class="container_citas">
                <div class="Alertas_calendario">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Citas </h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>


                <div class="calendario_citas">

                    <div class="row calendario_ext">
                        <div class="col-12 calendario_int">
                            <div id="calendario1" class="calendario1"></div>
                        </div>

                    </div>
                    <div class="col eventos_ext">

                        <div id="external-events" class="external-events">
                            <h4 class="text-center">Eventos Predefinidos</h4>
                            <div id="listaEventosPredefinidos" class="listaEventosPredefinidos">

                                <?php
                                require("coneccion.php");
                                $conexcion = regresarConexion();

                                $datos = mysqli_query($conexcion, "SELECT id, titulo, horaini, horafin, colortexto, colorfin FROM eventos_predefinidos");
                                $ep = mysqli_fetch_all($datos, MYSQLI_ASSOC);


                                foreach ($ep as $fila) {
                                    echo "<div class='fc-event' data-titulo='$fila[titulo]' data-horaini='$fila[horaini]' data-horafin='$fila[horafin]' data-colorfin='$fila[colorfin]' data-colortexto='$fila[colortexto]'
            style='color: $fila[colortexto]; background-color: $fila[colorfin]; margin: 2px; border-radius: 5px; padding: 2px; border: 2px solid Silver;'>
            $fila[titulo] [" . substr($fila['horaini'], 0, 5) . " a " . substr($fila['horafin'], 0, 5) . "]
           </div>";
                                }


                                ?>


                            </div>
                        </div>

                        <div class="" style="text-align: center; margin-right: 3em; font-size: 5px;">
                            <button type="button" id="botonEventosPredefinidos" class="btn btn-success">
                                Administrar Eventos Predefinidos
                            </button>
                        </div>

                    </div>


                    <!--Formulario de eventos inicio-->
                    <div class="modal fade" id="formularioEventos" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="" id="codigo" required="required">

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="">Titulo del Evento:</label>
                                            <input type="text" id="titulo" class="form-control" placeholder="">
                                        </div>
                                    </div>


                                    <div class="form-row d-flex">
                                        <div class="form-group col-md-6 p-1">
                                            <label for="">Fecha de Inicio</label>
                                            <div class="input-group" data-autoclose="true">
                                                <input type="date" id="fechaInicio" value="" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 p-1" id="tituloHoraInicio">
                                            <label for="">Hora de Inicio</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text" id="horaInicio" value="" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row d-flex ">
                                        <div class="form-group col-md-6 p-1">
                                            <label for="">Fecha de Fin</label>
                                            <div class="input-group" data-autoclose="true">
                                                <input type="date" id="fechaFin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 p-1" id="tituloHoraFin">
                                            <label for="">Hora de Fin</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text" id="horaFin" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <label for="">Descripcion:</label>
                                        <textarea id="descripcion" rows="3" class="form-control"></textarea>
                                    </div>

                                    <div class="form-row">
                                        <label for="">Color de fondo:</label>
                                        <input type="color" value="#3788d8" id="colorFondo" class="form-control" style="height: 36px;">
                                    </div>

                                    <div class="form-row">
                                        <label for="paciente">Paciente:</label>
                                        <select id="paciente" name="paciente" class="form-control" style="height: 36px;">
                                            <?php
                                            $servername = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $dbname = "consultorio_dental";

                                            $conexion = new mysqli($servername, $username, $password, $dbname);

                                            if ($conexion->connect_error) {
                                                die("Error de conexión a la base de datos: " . $conexion->connect_error);
                                            }

                                            $consulta_pacientes = "SELECT id, nombre, apellido_paterno, apellido_materno FROM pacientes";
                                            $resultado_pacientes = $conexion->query($consulta_pacientes);

                                            while ($fila_pacientes = $resultado_pacientes->fetch_assoc()) {
                                                $id_paciente = $fila_pacientes["id"];
                                                $nombre_paciente = $fila_pacientes["nombre"] . " " . $fila_pacientes["apellido_paterno"] . " " . $fila_pacientes["apellido_materno"];
                                                echo "<option value='$id_paciente'>$nombre_paciente</option>";
                                            }
                                            ?>
                                        </select>
                                    </div><br>


                                </div>

                                <div class="modal-footer">
                                    <button type="button" id="botonAgregar" class="btn btn-success">Agregar</button>
                                    <button type="button" id="botonModificar" class="btn btn-success">Modificar</button>
                                    <button type="button" id="botonBorrar" class="btn btn-success">Borrar</button>
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--Formulario de eventos fin-->


                </div>


                <div class="nota_pen">

                    <h3 class="titulo_bus">Historial de Citas</h3>
                    <div id="resultados_citas" class="result_general"></div>

                </div>


            </div>


        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            new FullCalendar.Draggable(document.getElementById('listaEventosPredefinidos'), {
                itemSelector: '.fc-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText.trim()
                    }
                }
            });

            $('.clockpicker').clockpicker();

            let calendario1 = new FullCalendar.Calendar(document.getElementById('calendario1'), {
                droppable: true,
                height: 530,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                editable: true,
                events: 'datos_eventos.php?accion=listar',

                dateClick: function(info) {
                    //alert(info.dateStr);
                    limpiarFormulario();
                    $('#botonAgregar').show();
                    $('#botonModificar').hide();
                    $('#botonBorrar').hide();

                    if (info.allDay) {
                        $('#fechaInicio').val(info.dateStr);
                        $('#fechaFin').val(info.dateStr);
                    } else {
                        let fechaHora = info.dateStr.split("T");
                        $('#fechaInicio').val(fechaHora[0]);
                        $('#fechaFin').val(fechaHora[0]);
                        $('#horaInicio').val(fechaHora[1].substring(0, 5));
                        $('#horaFin').val(fechaHora[1].substring(0, 5));
                    }

                    $("#formularioEventos").modal('show');
                },

                eventClick: function(infor) {

                    $('#botonAgregar').hide();
                    $('#botonModificar').show();
                    $('#botonBorrar').show();

                    $('#codigo').val(infor.event.id);
                    $('#titulo').val(infor.event.title);
                    $('#descripcion').val(infor.event.extendedProps.descripcion);
                    $('#fechaInicio').val(moment(infor.event.start).format("YYYY-MM-DD"));
                    $('#fechaFin').val(moment(infor.event.end).format("YYYY-MM-DD"));
                    $('#horaInicio').val(moment(infor.event.start).format("HH:mm"));
                    $('#horaFin').val(moment(infor.event.end).format("HH:mm"));
                    $('#paciente').val(infor.event.extendedProps.paciente);
                    $('#colorFondo').val(infor.event.backgroundColor);

                    console.log("ID del paciente seleccionado1:", $('#paciente').val());


                    $('#formularioEventos').modal('show');
                },

                eventResize: function(info) {
                    $('#codigo').val(info.event.id);
                    $('#titulo').val(info.event.title);
                    $('#descripcion').val(info.event.extendedProps.descripcion);
                    $('#fechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
                    $('#fechaFin').val(moment(info.event.end).format("YYYY-MM-DD"));
                    $('#horaInicio').val(moment(info.event.start).format("HH:mm"));
                    $('#horaFin').val(moment(info.event.end).format("HH:mm"));
                    $('#paciente').val(info.event.extendedProps.paciente);
                    $('#colorFondo').val(info.event.backgroundColor);


                    let registro = recuperarDatosFormulario();
                    modificarRegistro(registro);
                },

                eventDrop: function(unfo) {
                    $('#codigo').val(unfo.event.id);
                    $('#titulo').val(unfo.event.title);
                    $('#descripcion').val(unfo.event.extendedProps.descripcion);
                    $('#fechaInicio').val(moment(unfo.event.start).format("YYYY-MM-DD"));
                    $('#fechaFin').val(moment(unfo.event.end).format("YYYY-MM-DD"));
                    $('#horaInicio').val(moment(unfo.event.start).format("HH:mm"));
                    $('#horaFin').val(moment(unfo.event.end).format("HH:mm"));
                    $('#paciente').val(unfor.event.extendedProps.paciente);
                    $('#colorFondo').val(unfo.event.backgroundColor);

                    let registro = recuperarDatosFormulario();
                    modificarRegistro(registro);
                },

                drop: function(info) {
                    limpiarFormulario();
                    $('#colorFondo').val(info.draggedEl.dataset.colorfin);
                    $('#colortexto').val(info.draggedEl.dataset.colortexto);
                    $('#titulo').val(info.draggedEl.dataset.titulo);
                    let fechaHora = info.dateStr.split("T");
                    $('#fechaInicio').val(fechaHora[0]);
                    $('#fechaFin').val(fechaHora[0]);
                    if (info.allDay) {
                        $('#horaInicio').val(info.draggedEl.dataset.horaini);
                        $('#horaFin').val(info.draggedEl.dataset.horafin);
                    } else {
                        $('#horaInicio').val(fechaHora[1].substring(0, 5));
                        $('#horaFin').val(moment(fechaHora[1].substring(0, 5)).add(1, 'hours'));
                    }
                    let registro = recuperarDatosFormulario();
                    agregarEventoPredefinido(registro);
                }
            });
            calendario1.render();

            //Eventos de botones de la aplicacion

            $('#botonAgregar').click(function() {
                let registro = recuperarDatosFormulario();
                agregarRegistro(registro);
                $('#formularioEventos').modal('hide');
            });

            $('#botonModificar').click(function() {
                let registro = recuperarDatosFormulario();
                modificarRegistro(registro);
                $('#formularioEventos').modal('hide');
            });

            $('#botonBorrar').click(function() {
                let registro = recuperarDatosFormulario();
                borrarRegistro(registro);
                $('#formularioEventos').modal('hide');
            });


            $('#botonEventosPredefinidos').click(function() {
                window.location = "eventos_predefinidos.php";
            });

            //funcion para comunicnarse con el servidor AJAX

            function agregarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos_eventos.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hubo un error al agregar el evento" + error);
                    }
                });
            }

            function modificarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos_eventos.php?accion=modificar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hubo un error al modificar el evento" + error);
                    }
                });
            }

            function borrarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos_eventos.php?accion=eliminar',
                    data: registro,
                    success: function(msg) {
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hubo un error al borrar el evento" + error);
                    }
                });
            }

            function agregarEventoPredefinido(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'datos_eventos.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        calendario1.removeAllEvents();
                        calendario1.refetchEvents();
                    },
                    error: function(error) {
                        alert("Hubo un error al Agregar el evento Predefinido" + error);
                    }
                });
            }

            //funciones que interactuan con formulario eventos

            function limpiarFormulario() {
                $('#codigo').val('');
                $('#titulo').val('');
                $('#fechaInicio').val('');
                $('#horaInicio').val('');
                $('#fechaFin').val('');
                $('#horaFin').val('');
                $('#colorFondo').val('#3788D9');
                $('#paciente').val('');
                $('#descripcion').val('');
            }

            function recuperarDatosFormulario() {
                let registro = {
                    codigo: $('#codigo').val(),
                    titulo: $('#titulo').val(),
                    descripcion: $('#descripcion').val(),
                    inicio: $('#fechaInicio').val() + ' ' + $('#horaInicio').val(),
                    fin: $('#fechaFin').val() + ' ' + $('#horaFin').val(),
                    id_paciente: $('#paciente').val(),
                    colorfondo: $('#colorFondo').val()

                }
                return registro;
            }

        });
    </script>




    <script src="buscar_citas.js"></script>
    <script src="script.js"></script>
    <script src="notas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</body>

</html>