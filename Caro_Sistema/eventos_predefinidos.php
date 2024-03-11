<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION["username"])) {
    // Si no ha iniciado sesión, redirige a la página de inicio de sesión
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

    <!-------NOtasInicio--->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-------NOtasFIN--->

    <!---Links CSS-->
    <link rel="stylesheet" href="css/datatables.min.css">
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
                        <a href="citas.php"> <i class='bx bx-notepad icon'></i>
                            <span class="textos nav-text">Citas</span></a>
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

            <div class="container_eventos">
                <div class="Alertas">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Consultorio Dental </h6>
                            <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>
                <div class="eventos_predefinido">

                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h2 style="text-align:center; "> Administracion de Eventos Predefinidos</h2>

                                <table class="resultados tabla_eventos" id="tabla1" style="text-align:center;">
                                    <thead>
                                        <tr>
                                            <th>Evento</th>
                                            <th>Titulo</th>
                                            <th>Color de texto</th>
                                            <th>Color de fondo</th>
                                            <th>Hora de inicio</th>
                                            <th>Hora de fin</th>
                                            <th>Borrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <div style="text-align: center;">
                                    <button type="button" id="botonAgregar" class="btn btn-success">Agregar evento predefinido</button>
                                    <button type="button" id="botonSalir" class="btn btn-success">Regresar al calendario</button>


                                </div>

                            </div>
                        </div>

                        <!-----Formulario para agregar Eventos Predefinidos--------->

                        <div class="modal fade" id="formularioEventosPredefinidos" tabindex="-1">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="close">
                                            <span aria-hidden="true">x</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label>Evento Predefinido:</label>
                                                <input type="text" id="titulo" name="titulo" class="form-control" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Color Fondo:</label>
                                            <input type="color" value="#3788D8" id="colorFondo" class="form-control" style="height: 36px;">
                                        </div>
                                        <div class="form-group">
                                            <label>Color Texto:</label>
                                            <input type="color" value="#262626" id="colorTexto" class="form-control" style="height: 36px;">
                                        </div>

                                        <div class="form-group">
                                            <label>Hora inicio:</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text" id="horaInicio" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Hora Fin:</label>
                                            <div class="input-group clockpicker" data-autoclose="true">
                                                <input type="text" id="horaFin" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" id="botonConfirmarAgregar" class="btn btn-success">Agregar</button>
                                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!----- FIN Formulario para agregar Eventos Predefinidos--------->

                    </div>

                </div>
            </div>



        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            $('.clockpicker').clockpicker();

            let tabla1 = $('#tabla1').DataTable({
                "ajax": {
                    url: 'eventos_prede.php?accion=listar',
                    dataSrc: ""
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "titulo"
                    },
                    {
                        "data": "colortexto"
                    },
                    {
                        "data": "colorfin"
                    },
                    {
                        "data": "horaini"
                    },
                    {
                        "data": "horafin"
                    },
                    {
                        "data": "null",
                        "orderable": false
                    }
                ],
                columnDefs: [{
                        targets: -1,
                        className: 'dt-body-center',
                        "defaultContent": "<button class='btn btn-sm btn-danger botonborrar'>Borrar</button>",
                        data: null
                    },
                    {
                        targets: 1,
                        className: 'dt-body-center'
                    },
                    {
                        targets: 2,
                        className: 'dt-body-center'
                    }
                ],
                'rowCallback': function(row, data, index) {
                    $(row).find('td:eq(1)').css('color', data.colortexto);
                    $(row).find('td:eq(1)').css('background-color', data.colorfin);
                },
                "languaje": {
                    "url": "datatables/spanish.json"
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],

            });

            $('#tabla1 tbody').on('click', 'button.botonborrar', function() {
                if (confirm("¿Seguro que quieres eliminar este evento?")) {
                    let registro = tabla1.row($(this).parents('tr')).data();
                    borrarRegistro(registro);
                }
            });
            //eventos de botones del formulario

            $('#botonAgregar').click(function() {
                $('#formularioEventosPredefinidos').modal('show');
            });

            $('#botonConfirmarAgregar').click(function() {
                let registro = recuperarDatosFormulario();
                agregarRegistro(registro);
                $('#formularioEventosPredefinidos').modal('hide');
            });

            $('#botonSalir').click(function() {
                window.location = "citas.php";
            });


            //Funciones para comunicarse con AJAX

            function agregarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'eventos_prede.php?accion=agregar',
                    data: registro,
                    success: function(msg) {
                        tabla1.ajax.reload();
                    },
                    error: function(error) {
                        alert("Hubo un error al agregar el evento:" + error);
                    }
                });
            }

            function borrarRegistro(registro) {
                $.ajax({
                    type: 'POST',
                    url: 'eventos_prede.php?accion=borrar',
                    data: registro,
                    success: function(msg) {
                        tabla1.ajax.reload();
                    },
                    error: function(error) {
                        alert("Hubo un error al borrar el evento:" + error);
                    }
                });
            }


            //Funciones para el formulario de eventos

            function limpiarFormulario() {
                $('#titulo').val('');
                $('#horaInicio').val('');
                $('#horaFin').val('');
                $('#colorTexto').val('');
                $('#colorFondo').val('');
            }

            function recuperarDatosFormulario() {
                let registro = {
                    titulo: $('#titulo').val(),
                    horaini: $('#horaInicio').val(),
                    horafin: $('#horaFin').val(),
                    colorfin: $('#colorFondo').val(),
                    colortexto: $('#colorTexto').val()
                };
                return registro;
            }

        });
    </script>

    <script src="script.js"></script>
    <script src="notas.js"></script>
</body>

</html>