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

    <!-------NOtasInicio--->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-------NOtasFIN--->
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

            <div class="containe">

                <div class="Alertas">
                    <div class="bienvenido">
                        <div>
                            <h3>Bienvenida, <?php echo $_SESSION["username"]; ?>!</h3>
                        </div>
                        <div>
                            <h6>Consultorio Dental </h6> <div id="fecha"></div>
                        </div>
                        <div>
                            <img src="images/small_smile.png" alt="" width="200px" height="100px">
                        </div>
                    </div>
                </div>
                <div class="lado_izquierdo">

                <h4 class="titulo_bus">Citas Actuales</h4>
                    <div id="resultados_citas" class="result_general"></div>

                </div>



                <div class="derecho_1">

                    <div class="containe_notas">
                        <div class="perfil">  
                            <span>Vamos a cumplir tus metas</span>
                        </div>

                        <div class="agregar-tarea">
                            <input type="text" id="input" placeholder="agregar una tarea" style="color: purple">
                            <i id="boton-enter" class="fas fa-plus-circle"></i>
                        </div>


                        <div class="seccion-tarea">
                            <h3>Estas son tus Tareas Pendientes </h3>
                            <ul id="lista">
                                <!-- <li id="elemento">
                <i class="far fa-circle co" data="realizado" id="0"></i>
                <p class="text"> Hacer la tarea de matematicas </p>
                <i class="fas fa-trash de" data="eliminado" id="0"></i> 
            </li>
            <li id="elemento">
                <i class="far fa-circle co" data="realizado" id="0"></i>
                <p class="text"> Lavar la ropa </p>
                <i class="fas fa-trash de" data="eliminado" id="0"></i> 
            </li> -->
                            </ul>
                        </div>
                    </div>


                </div>
                <div class="derecho_2">
                <h4 class="titulo_bus">Pacientes Deudores</h4>
                    <div id="resultados_deudas" class="result_deudad"></div>
                </div>
            </div>


        </div>
    </section>
    
    <script src="script.js"></script>
    <script src="notas.js"></script>
    <script src="busqueda_gene_tablas.js"></script>
   
</body>

</html>