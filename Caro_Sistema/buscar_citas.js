//Busqueda general Doctores
document.addEventListener("DOMContentLoaded", function() {
    buscarCitas(); // Llamada a la función buscarCitas() al cargar la página
});
function buscarCitas() {
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4 && xhry.status == 200) {
            var usersd = JSON.parse(xhry.responseText);
            displayUsers(usersd);
        }
    };
    xhry.open("GET", "busqueda_gen_cita.php", true);
    xhry.send();
}

function displayUsers(usersd) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>Numero de Cita</th><th>Descripcion</th><th>Paciente</th><th>Fecha de la cita</th><th>Estatus cita</th><th>Ver más</th></tr>";
    
    for (var i = 0; i < usersd.length; i++) {
        table2 += "<tr>";
        table2 += "<td hidden>" + usersd[i].id + "</td>";
        table2 += "<td>" + usersd[i].codigo_eventos + "</td>";
        table2 += "<td>" + usersd[i].descripcion_cita + "</td>";
        table2 += "<td id='nombre_paciente_" + usersd[i].id_paciente + "'></td>"; // Placeholder para el nombre del paciente
        table2 += "<td>" + usersd[i].fecha + "</td>";
    
        var estatus = usersd[i].estatus_cita;
        var color = "";
        var emoticono = "";
        if (estatus == "Activa") {
            color = "#1ce937c2"; // Verde
            emoticono = "😊"; // Carita sonriente
        } else if (estatus == "Cancelada") {
            color = "#e91c1cc2"; // Rojo
            emoticono = "😡"; // Carita enojada
        } else if (estatus == "Concluida") {
            color = "#1c9ee9c2"; // Azul
            emoticono = "😴"; // Carita durmiendo
        }
    
        table2 += "<td style='background-color: " + color + "; font-weight: bold; color: white;'>" + estatus + " " + emoticono + "</td>";

        table2 += "<td><button onclick='editarCitas(" + usersd[i].id + ")'>Editar</button></td>";
        table2 += "</tr>";
    }
    
    table2 += "</table>";
    

    // Mostrar la tabla en el elemento con id "resultados_citas"
    document.getElementById("resultados_citas").innerHTML = table2;

    // Agregar evento change al select de pacientes para obtener el nombre del paciente
    var idPacientes = usersd.map(user => user.id_paciente); // Obtener array de ids de pacientes
    idPacientes.forEach(idPaciente => {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obtener_nombre_paciente.php?id=" + idPaciente, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Actualizar el campo de nombre del paciente con el nombre obtenido
                    document.getElementById("nombre_paciente_" + idPaciente).textContent = xhr.responseText;
                } else {
                    // Manejar errores si la solicitud no se completa correctamente
                    console.error("Error al obtener el nombre del paciente. Estado de la solicitud: " + xhr.status);
                }
            }
        };
        xhr.send();
    });
}




// Función para editar un usuario
function editarCitas(idCita) {
    // Ejemplo de abrir un formulario en una nueva ventana (ajusta según tus necesidades)
    var formularioHTML = `
        <h2>Editar Cita</h2>
        <form id="formularioEditar">

            <label for="descricpionEditar">Descripcion Cita:</label>
            <input type="text" id="descricpionEditar" name="descricpionEditar" readonly ><br>

            <label for="fechaEditar">Fecha Cita:</label>
            <input type="text" id="fechaEditar" name="fechaEditar" readonly><br>

            <label for="id_pacienteEditar">Paciente:</label>
            <select name="id_pacienteEditar" id="id_pacienteEditar" readonly>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "consultorio_dental";
            
                $conexion = new mysqli($servername, $username, $password, $dbname);
            
                if ($conexion->connect_error) {
                    die("Error de conexión a la base de datos: " . $conexion->connect_error);
                }
            
                $consulta_paciente = "SELECT id, nombre, apellido_paterno, apellido_materno FROM pacientes";
                $resultado_paciente = $conexion->query($consulta_paciente);
            
                while ($fila_paciente = $resultado_paciente->fetch_assoc()) {
                    $id_paciente = $fila_paciente["id"];
                    $nombre_paciente = $fila_paciente["nombre"];
                    $apellido_paterno = $fila_paciente["apellido_paterno"];
                    $apellido_materno = $fila_paciente["apellido_materno"];
                    $nombre_completo = $nombre_paciente . " " . $apellido_paterno . " " . $apellido_materno;
            
                    // Agregamos líneas para depurar
                    echo "<option value="$id_paciente"></option>";
                }
            
                $conexion->close();
                ?>
            </select><br>

            <label for="id_tratamientoEditar">Tratamiento :</label>
            <select name="id_tratamientoEditar" id="id_tratamientoEditar" >
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

            <label for="costo_tratamientoEditar">Costo Tratamiento:</label>
            <input type="text" id="costo_tratamientoEditar" name="costo_tratamientoEditar" readonly><br>            

            <label for="costo_consultaEditar">Costo Consulta:</label>
            <input type="text" id="costo_consultaEditar" name="costo_consultaEditar"><br>

            <label for="costo_totalEditar">Costo Total:</label>
            <input type="text" id="costo_totalEditar" name="costo_totalEditar" readonly><br>

            <label for="costo_pagadoEditar">Costo Pagado:</label>
            <input type="text" id="costo_pagadoEditar" name="costo_pagadoEditar" required><br>

            <label for="deuda_totalEditar">Total de Deuda:</label>
            <input type="text" id="deuda_totalEditar" name="deuda_totalEditar" readonly><br>

            <label for="estatus_dedudaEditar">Estatus Deuda:</label>
            <input type="text" id="estatus_dedudaEditar" name="estatus_dedudaEditar" readonly><br>

            <label for="estatus_citaEditar">Estatus Cita:</label>
            <select id="estatus_citaEditar" name="estatus_citaEditar" required>
                <option value="Activa">Activa</option>
                <option value="Concluida">Concluida</option>
                <option value="Cancelada">Cancelada</option>
            </select><br>    

            <label for="fecha_pagoEditar">Fecha Pago:</label>
            <input type="text" id="fecha_pagoEditar" name="fecha_pagoEditar" readonly><br>

            <label for="codigo_eventosEditar">Numero de Cita:</label>
            <input type="text" id="codigo_eventosEditar" name="codigo_eventosEditar" readonly><br>

            
            <input type="submit" value="Guardar cambios">
        </form>
    `;

    Swal.fire({
        title: '',
        html: formularioHTML,
        showCancelButton: true,
        showConfirmButton: false
    });

    
    // Función para calcular y actualizar el costo total
    function calcularCostoTotal() {
        var costoTratamiento = parseFloat(document.getElementById('costo_tratamientoEditar').value) || 0;
        var costoConsulta = parseFloat(document.getElementById('costo_consultaEditar').value) || 0;
        var costoTotal = costoTratamiento + costoConsulta;
        document.getElementById('costo_totalEditar').value = costoTotal;
    }

    // Agregar oyentes de eventos para los campos costo_tratamientoEditar y costo_consultaEditar
    document.getElementById('costo_tratamientoEditar').addEventListener('input', calcularCostoTotal);
    document.getElementById('costo_consultaEditar').addEventListener('input', calcularCostoTotal);

    // Calcular el costo total al cargar la página
    calcularCostoTotal();



// Función para calcular y actualizar la deuda total
function calcularDeudaTotal() {
    var costoTotal = parseFloat(document.getElementById('costo_totalEditar').value) || 0;
    var costoPagado = parseFloat(document.getElementById('costo_pagadoEditar').value) || 0;
    var deudaTotal = costoTotal - costoPagado;
    document.getElementById('deuda_totalEditar').value = deudaTotal;

    // Verificar si la deuda total es cero y actualizar el estatus de la deuda
    var estatusDeuda = document.getElementById('estatus_dedudaEditar');
    if (deudaTotal <= 0) {
        estatusDeuda.value = 'Pagada';

        // Obtener la fecha actual y establecerla en el campo fecha_pagoEditar
        var fechaPago = new Date().toISOString().slice(0, 10);
        document.getElementById('fecha_pagoEditar').value = fechaPago;
    } else {
        estatusDeuda.value = 'Pendiente'; // Limpiar el valor si no está pagada
    }
}

// Agregar oyente de eventos para el campo costo_pagadoEditar
document.getElementById('costo_pagadoEditar').addEventListener('input', calcularDeudaTotal);

// Calcular la deuda total al cargar la página
calcularDeudaTotal();






// Luego, utiliza AJAX para obtener la lista de doctores y actualizar el <select>
var selectPaciente = document.getElementById("id_pacienteEditar");

var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
        // La respuesta del servidor contiene las opciones para el select
        var opcionesPacientes = JSON.parse(xhr.responseText);

        // Agrega las opciones al select
        opcionesPacientes.forEach(function(pacientes) {
            var option = document.createElement("option");
            option.value = pacientes.id;
            option.text = pacientes.nombre_completo;
            selectPaciente.add(option);
        });
    }
};

// Realiza la solicitud AJAX al archivo PHP que obtiene la lista de doctores
xhr.open("GET", "obtencion_pacientes.php", true);
xhr.send();


    // Luego, utiliza AJAX para obtener la lista de tratamientos y actualizar el <select>
var selectTratamiento = document.getElementById("id_tratamientoEditar");

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


// Agregar evento change al select de tratamientos
// Agregar evento change al select de tratamientos
document.getElementById("id_tratamientoEditar").addEventListener("change", function() {
    // Obtener el valor seleccionado del select
    var idTratamiento = this.value;
    
    // Verificar que el ID del tratamiento no esté vacío
    if (idTratamiento) {
        // Realizar una solicitud AJAX para obtener el costo del tratamiento
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obtener_costo_tratamiento.php?id=" + idTratamiento, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Actualizar el campo de costo del tratamiento con el costo obtenido
                    document.getElementById("costo_tratamientoEditar").value = xhr.responseText;
                } else {
                    // Manejar errores si la solicitud no se completa correctamente
                    console.error("Error al obtener el costo del tratamiento. Estado de la solicitud: " + xhr.status);
                }
            }
        };
        xhr.send();
    } else {
        // Manejar el caso en que no se seleccione ningún tratamiento
        console.warn("No se ha seleccionado ningún tratamiento.");
    }
});


    // Obtener el formulario y llenar los campos con los datos actuales del usuario
    var formulario = document.getElementById('formularioEditar');
    var descricpionEditar = document.getElementById('descricpionEditar');
    var fechaEditar = document.getElementById('fechaEditar');
    var id_pacienteEditar = document.getElementById('id_pacienteEditar');
    var id_tratamientoEditar = document.getElementById('id_tratamientoEditar');
    var costo_tratamientoEditar = document.getElementById('costo_tratamientoEditar');
    var costo_consultaEditar = document.getElementById('costo_consultaEditar');
    var costo_totalEditar = document.getElementById('costo_totalEditar');
    var costo_pagadoEditar = document.getElementById('costo_pagadoEditar');
    var deuda_totalEditar = document.getElementById('deuda_totalEditar');
    var estatus_dedudaEditar = document.getElementById('estatus_dedudaEditar');
    var estatus_citaEditar = document.getElementById('estatus_citaEditar');
    var fecha_pagoEditar = document.getElementById('fecha_pagoEditar');
    var codigo_eventosEditar = document.getElementById('codigo_eventosEditar');

    // Obtener los datos actuales del usuario para prellenar el formulario
    fetch('obtener_cita.php?id=' + idCita)
        .then(response => response.json())
        .then(data => {
            console.log('Datos del usuario:', data); // Imprimir datos en la consola
            // Llenar los campos del formulario con los datos obtenidos
            descricpionEditar.value = data.success ? data.data.descripcion_cita : '';
            codigo_eventosEditar.value = data.success ? data.data.codigo_eventos : '';
            fechaEditar.value = data.success ? data.data.fecha : '';
            id_pacienteEditar.value = data.success ? data.data.id_paciente : '';
            id_tratamientoEditar.value = data.success ? data.data.id_tratamiento : '';
            costo_tratamientoEditar.value = data.success ? data.data.costo_tratamiento: '';
            costo_consultaEditar.value = data.success ? data.data.consulta_costo: '';
            costo_totalEditar.value = data.success ? data.data.costo_total: '';
            costo_pagadoEditar.value = data.success ? data.data.costo_pagado: '';
            deuda_totalEditar.value = data.success ? data.data.total_deuda: '';
            estatus_dedudaEditar.value = data.success ? data.data.estatus_deuda: '';
            estatus_citaEditar.value = data.success ? data.data.estatus_cita: '';
            fecha_pagoEditar.value = data.success ? data.data.fecha_pago: '';
        })
        .catch(error => console.error('Error:', error));

    // Evento de envío del formulario
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        var nuevodescripcion = descricpionEditar.value;
        var nuevocodigo_eventos = codigo_eventosEditar.value;
        var nuevofecha = fechaEditar.value;
        var nuevoid_paciente = id_pacienteEditar.value;
        var nuevoid_tratamiento = id_tratamientoEditar.value;
        var nuevocosto_tratamiento = costo_tratamientoEditar.value;
        var nuevocosto_consulta = costo_consultaEditar.value;
        var nuevocosto_total = costo_totalEditar.value;
        var nuevocosto_pagado = costo_pagadoEditar.value;
        var nuevodeuda_total = deuda_totalEditar.value;
        var nuevoestatus_deuda = estatus_dedudaEditar.value;
        var nuevoestatus_cita = estatus_citaEditar.value;
        var nuevofecha_pago = fecha_pagoEditar.value;

        // Aquí puedes realizar una solicitud AJAX para actualizar los datos del usuario en el servidor
        fetch('actualizar_cita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idCita + '&descripcion_cita=' + encodeURIComponent(nuevodescripcion) + '&codigo_eventos=' + encodeURIComponent(nuevocodigo_eventos) + '&fecha=' + encodeURIComponent(nuevofecha) + '&id_paciente=' + encodeURIComponent(nuevoid_paciente) + '&id_tratamiento=' + encodeURIComponent(nuevoid_tratamiento) + '&costo_tratamiento=' + encodeURIComponent(nuevocosto_tratamiento) + '&consulta_costo=' + encodeURIComponent(nuevocosto_consulta) + '&costo_total=' + encodeURIComponent(nuevocosto_total) + '&costo_pagado=' + encodeURIComponent(nuevocosto_pagado) + '&total_deuda=' + encodeURIComponent(nuevodeuda_total) + '&estatus_deuda=' + encodeURIComponent(nuevoestatus_deuda) + '&estatus_cita=' + encodeURIComponent(nuevoestatus_cita) + '&fecha_pago=' + encodeURIComponent(nuevofecha_pago),
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
setInterval(obtenerDatos, 5000); // Cada 5 segundos