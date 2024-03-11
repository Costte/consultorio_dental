// Función para eliminar un usuario
function eliminarUsuario(idUsuario) {
    // Mostrar alerta para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará al paciente. ¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí puedes realizar una solicitud AJAX para eliminar el usuario en el servidor
            fetch('eliminar_paciente.php?id=' + idUsuario, {
                method: 'GET',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Eliminación exitosa, actualizar los resultados
                    Swal.fire({
                        title: 'Éxito',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Realizar una nueva búsqueda o actualizar la tabla de resultados
                        realizarBusqueda(ObtenerValoresDeBusquedaActuales());
                    });
                } else {
                    // Error al eliminar, mostrar mensaje de error
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
}

// Función para editar un usuario
function editarUsuario(idUsuario) {
    // Ejemplo de abrir un formulario en una nueva ventana (ajusta según tus necesidades)
    var formularioHTML = `
        <h2>Editar Paciente</h2>
        <form id="formularioEditar">

            <label for="nombreEditar">Nombre:</label>
            <input type="text" id="nombreEditar" name="nombreEditar" required><br>

            <label for="apellido_paternoEditar">Apellido Peterno:</label>
            <input type="text" id="apellido_paternoEditar" name="apellido_paternoEditar" required><br>

            <label for="apellido_maternoEditar">Apellido Materno:</label>
            <input type="text" id="apellido_maternoEditar" name="apellido_maternoEditar" required><br>

            <label for="fecha_nacimientoEditar">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimientoEditar" name="fecha_nacimientoEditar" required><br>

            <label for="edadEditar">Edad :</label>
            <input type="number" id="edadEditar" name="edadEditar" required><br>

            <label for="telefonoEditar">Celular :</label>
            <input type="tel" id="telefonoEditar" name="telefonoEditar" maxlength="10" required><br>

            <label for="correoEditar">Email :</label>
            <input type="email" id="correoEditar" name="correoEditar" required><br>

            <label for="sexoEditar" class="label">Sexo:</label>
            <select id="sexoEditar" name="sexoEditar" aria-label="Floating label select example" required class="input">
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
            </select> <br>

            <label for="fecha_ingresoEditar">Fecha de Ingreso :</label>
            <input type="date" id="fecha_ingresoEditar" name="fecha_ingresoEditar" readonly><br>

            <label for="alergiasEditar" class="label">Alergias :</label>
            <textarea id="alergiasEditar" name="alergiasEditar" rows="2" class="input"></textarea><br>

            <label for="doctorEditar">Doctor:</label>
            <select name="doctorEditar" id="doctorEditar" required>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "consultorio_dental";
            
                $conexion = new mysqli($servername, $username, $password, $dbname);
            
                if ($conexion->connect_error) {
                    die("Error de conexión a la base de datos: " . $conexion->connect_error);
                }
            
                $consulta_doctores = "SELECT id, nombre, apellido_paterno FROM doctores";
                $resultado_doctores = $conexion->query($consulta_doctores);
            
                while ($fila_doctor = $resultado_doctores->fetch_assoc()) {
                    $id_doctor = $fila_doctor["id"];
                    $nombre_doctor = $fila_doctor["nombre"];
                    $apellido_paterno = $fila_doctor["apellido_paterno"];
                    $nombre_completo = $nombre_doctor . " " . $apellido_paterno;
            
                    // Agregamos líneas para depurar
                    echo "<option value="$id_doctor"></option>";
                }
            
                $conexion->close();
                ?>
            </select><br>

            <label for="tratamientoEditar">Ultimo Tratamiento :</label>
            <select name="tratamientoEditar" id="tratamientoEditar" required>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "consultorio_dental";
        
            $conexion = new mysqli($servername, $username, $password, $dbname);
        
            if ($conexion->connect_error) {
                die("Error de conexión a la base de datos: " . $conexion->connect_error);
            }
            
                $consulta_tratamiento = "SELECT id, nombre FROM tratamientos";
                $resultado_tratamiento  = $conexion->query($consulta_tratamiento);
            
                while ($fila_tratamiento = $resultado_tratamiento->fetch_assoc()) {
                    $id_tratamiento = $fila_tratamiento["id"];
                    $nombre_tratamiento = $fila_tratamiento["nombre"];
            
            
                    // Agregamos líneas para depurar
                    echo "<option value="$id_tratamiento"></option>";
                }
            
                $conexion->close();
                ?>
            </select><br>
            
            <input type="submit" value="Guardar cambios">
        </form>`;

        Swal.fire({
            title: '',
            html: formularioHTML,
            showCancelButton: true,
            showConfirmButton: false,
            // Aquí se agrega el evento para manejar el envío del formulario
            onBeforeOpen: () => {
                const form = document.getElementById('formularioEditar');
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Evita el envío tradicional del formulario
                    // Aquí puedes agregar tu código para enviar los datos del formulario con AJAX
                    // Por ejemplo:
                    // sendDataWithAjax();
                    // Y luego, cierras el modal después de enviar los datos:
                    Swal.close();
                });
            }
        });



// Luego, utiliza AJAX para obtener la lista de doctores y actualizar el <select>
var selectDoctor = document.getElementById("doctorEditar");

var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        // La respuesta del servidor contiene las opciones para el select
        var opcionesDoctores = JSON.parse(xhr.responseText);

        // Agrega las opciones al select
        opcionesDoctores.forEach(function(doctor) {
            var option = document.createElement("option");
            option.value = doctor.id;
            option.text = doctor.nombre_completo;
            selectDoctor.add(option);
        });
    }
};

// Realiza la solicitud AJAX al archivo PHP que obtiene la lista de doctores
xhr.open("GET", "obtencion_doctores.php", true);
xhr.send();



// Luego, utiliza AJAX para obtener la lista de tratamientos y actualizar el <select>
var selectTratamiento = document.getElementById("tratamientoEditar");

var xhs = new XMLHttpRequest();
xhs.onreadystatechange = function() {
    if (xhs.readyState === 4 && xhs.status === 200) {
        // La respuesta del servidor contiene las opciones para el select
        var opcionesTratamientos = JSON.parse(xhs.responseText);

        // Agrega las opciones al select
        opcionesTratamientos.forEach(function(tratamiento) {
            var optionn = document.createElement("option");
            optionn.value = tratamiento.id;
            optionn.text = tratamiento.nombre;
            selectTratamiento.add(optionn);
        });
    }
};

// Realiza la solicitud AJAX al archivo PHP que obtiene la lista de tratamientos
xhs.open("GET", "obtencion_tratamientos.php", true);
xhs.send();


    // Obtener el formulario y llenar los campos con los datos actuales del usuario
    var formulario = document.getElementById('formularioEditar');
    var nombreEditar = document.getElementById('nombreEditar');
    var apellido_paternoEditar = document.getElementById('apellido_paternoEditar');
    var apellido_maternoEditar = document.getElementById('apellido_maternoEditar');
    var fecha_nacimientoEditar = document.getElementById('fecha_nacimientoEditar');
    var edadEditar = document.getElementById('edadEditar');
    var telefonoEditar = document.getElementById('telefonoEditar');
    var correoEditar = document.getElementById('correoEditar');
    var sexoEditar = document.getElementById('sexoEditar');
    var fecha_ingresoEditar = document.getElementById('fecha_ingresoEditar');
    var alergiasEditar = document.getElementById('alergiasEditar');
    var doctorEditar = document.getElementById('doctorEditar');
    var tratamientoEditar = document.getElementById('tratamientoEditar');

    // Obtener los datos actuales del usuario para prellenar el formulario
    fetch('obtener_paciente.php?id=' + idUsuario)
        .then(response => response.json())
        .then(data => {
            console.log('Datos del usuario:', data); // Imprimir datos en la consola
            // Llenar los campos del formulario con los datos obtenidos
            nombreEditar.value = data.success ? data.data.nombre : '';
            apellido_paternoEditar.value = data.success ? data.data.apellido_paterno : '';
            apellido_maternoEditar.value = data.success ? data.data.apellido_materno : '';
            fecha_nacimientoEditar.value = data.success ? data.data.fecha_nacimiento : '';
            edadEditar.value = data.success ? data.data.edad : '';
            telefonoEditar.value = data.success ? data.data.telefono : '';
            correoEditar.value = data.success ? data.data.correo : '';
            sexoEditar.value = data.success ? data.data.sexo : '';
            fecha_ingresoEditar.value = data.success ? data.data.fecha_ingreso: '';
            alergiasEditar.value = data.success ? data.data.alergias : '';
            doctorEditar.value = data.success ? data.data.id_doctor : '';
            tratamientoEditar.value = data.success ? data.data.id_tratamiento : '';
        })
        .catch(error => console.error('Error:', error));

    // Evento de envío del formulario
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        var nuevoNombre = nombreEditar.value;
        var nuevoApellido_paterno = apellido_paternoEditar.value;
        var nuevoApellido_materno = apellido_maternoEditar.value;
        var nuevofecha_nacimiento = fecha_nacimientoEditar.value;
        var nuevoedad = edadEditar.value;
        var nuevotelefono = telefonoEditar.value;
        var nuevocorreo = correoEditar.value;
        var nuevosexo = sexoEditar.value;
        var nuevofecha_ingreso = fecha_ingresoEditar.value;
        var nuevoalergias = alergiasEditar.value;
        var nuevodoctor = doctorEditar.value;
        var nuevotratamiento = tratamientoEditar.value;

        // Aquí puedes realizar una solicitud AJAX para actualizar los datos del usuario en el servidor
        fetch('actualizar_paciente.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idUsuario + '&nombre=' + encodeURIComponent(nuevoNombre) + '&apellido_paterno=' + encodeURIComponent(nuevoApellido_paterno) + '&apellido_materno=' + encodeURIComponent(nuevoApellido_materno) + '&fecha_nacimiento=' + encodeURIComponent(nuevofecha_nacimiento) + '&edad=' + encodeURIComponent(nuevoedad) + '&telefono=' + encodeURIComponent(nuevotelefono) + '&correo=' + encodeURIComponent(nuevocorreo) + '&sexo=' + encodeURIComponent(nuevosexo) + '&fecha_ingreso=' + encodeURIComponent(nuevofecha_ingreso) + '&alergias=' + encodeURIComponent(nuevoalergias) + '&id_doctor=' + encodeURIComponent(nuevodoctor) + '&id_tratamiento=' + encodeURIComponent(nuevotratamiento),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualización exitosa, cerrar la ventana/modal de edición
                Swal.close();

                // Recargar la tabla con los datos actualizados
                realizarBusqueda(ObtenerValoresDeBusquedaActuales());
            } else {
                // Mostrar mensaje de error
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => console.error('Error:', error));
    });
}


//Busqueda general pacientes

function buscarUsuarios() {
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var users = JSON.parse(xhr.responseText);
            displayUsers(users);
        }
    };
    xhr.open("GET", "busqueda_gen_paci.php", true);
    xhr.send();
}

function displayUsers(users) {
    // Crear la tabla HTML con los usuarios
    var table = "<table class='result_general'>";
    table += "<tr><th>ID</th><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Fecha de ingreso</th><th>Ultimo Tratamiento</th></tr>";

    for (var o = 0; o < users.length; o++) {
        table += "<tr>";
        table += "<td>" + users[o].id + "</td>";
        table += "<td>" + users[o].nombre + "</td>";
        table += "<td>" + users[o].apellido_paterno + "</td>";
        table += "<td>" + users[o].apellido_materno + "</td>";
        table += "<td>" + users[o].fecha_ingreso + "</td>";
        table += "<td>" + users[o].nombre_tratamiento + "</td>";
        table += "</tr>";
    }

    table += "</table>";

    // Mostrar la tabla en el elemento con id "userTable"
    document.getElementById("resultados").innerHTML = table;
}


