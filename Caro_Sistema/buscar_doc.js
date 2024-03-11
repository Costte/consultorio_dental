//Busqueda general Doctores

function buscarDoctores() {
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4 && xhry.status == 200) {
            var usersd = JSON.parse(xhry.responseText);
            displayUsers(usersd);
        }
    };
    xhry.open("GET", "busqueda_gen_doc.php", true);
    xhry.send();
}

function displayUsers(usersd) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>ID</th><th>Nombre</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>Cedula</th><th>Especialidad</th><th>Fecha Ingreso</th><th>Editar</th><th>Eliminar</th></tr>";

    for (var i = 0; i < usersd.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersd[i].id + "</td>";
        table2 += "<td>" + usersd[i].nombre + "</td>";
        table2 += "<td>" + usersd[i].apellido_paterno + "</td>";
        table2 += "<td>" + usersd[i].apellido_materno + "</td>";
        table2 += "<td>" + usersd[i].cedula + "</td>";
        table2 += "<td>" + usersd[i].especialidad + "</td>";
        table2 += "<td>" + usersd[i].fecha_ingreso + "</td>";
        table2 += "<td><button onclick='editarDoctor(" + usersd[i].id + ")'>Editar</button></td>";
        table2 += "<td><button onclick='eliminarDoctor(" + usersd[i].id + ")'>Eliminar</button></td>";
        table2 += "</tr>";
    }

    table2 += "</table>";

    // Mostrar la tabla en el elemento con id "userTable"
    document.getElementById("resultados_doc").innerHTML = table2;
}


// Función para eliminar un usuario
function eliminarDoctor(idDoc) {
    // Mostrar alerta para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará al doctor. ¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí puedes realizar una solicitud AJAX para eliminar el usuario en el servidor
            fetch('eliminar_doctor.php?id=' + idDoc, {
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
function editarDoctor(idDoc) {
    // Ejemplo de abrir un formulario en una nueva ventana (ajusta según tus necesidades)
    var formularioHTML = `
        <h2>Editar Doctor</h2>
        <form id="formularioEditar">

            <label for="nombreEditar">Nombre:</label>
            <input type="text" id="nombreEditar" name="nombreEditar" required><br>

            <label for="apellido_paternoEditar">Apellido Peterno:</label>
            <input type="text" id="apellido_paternoEditar" name="apellido_paternoEditar" required><br>

            <label for="apellido_maternoEditar">Apellido Materno:</label>
            <input type="text" id="apellido_maternoEditar" name="apellido_maternoEditar" required><br>

            <label for="cedulaEditar">Cedula:</label>
            <input type="text" id="cedulaEditar" name="cedulaEditarr" maxlength="8" required><br>

            <label for="especialidadEditar">Especialidad :</label>
            <input type="text" id="especialidadEditar" name="especialidadEditar" required><br>

            <label for="fecha_ingresoEditar">Fecha de Ingreso :</label>
            <input type="date" id="fecha_ingresoEditar" name="fecha_ingresoEditar" readonly><br>
            
            <input type="submit" value="Guardar cambios">
        </form>
    `;

    Swal.fire({
        title: '',
        html: formularioHTML,
        showCancelButton: true,
        showConfirmButton: false
    });

    // Obtener el formulario y llenar los campos con los datos actuales del usuario
    var formulario = document.getElementById('formularioEditar');
    var nombreEditar = document.getElementById('nombreEditar');
    var apellido_paternoEditar = document.getElementById('apellido_paternoEditar');
    var apellido_maternoEditar = document.getElementById('apellido_maternoEditar');
    var cedulaEditar = document.getElementById('cedulaEditar');
    var especialidadEditar = document.getElementById('especialidadEditar');
    var fecha_ingresoEditar = document.getElementById('fecha_ingresoEditar');

    // Obtener los datos actuales del usuario para prellenar el formulario
    fetch('obtener_doctor.php?id=' + idDoc)
        .then(response => response.json())
        .then(data => {
            console.log('Datos del usuario:', data); // Imprimir datos en la consola
            // Llenar los campos del formulario con los datos obtenidos
            nombreEditar.value = data.success ? data.data.nombre : '';
            apellido_paternoEditar.value = data.success ? data.data.apellido_paterno : '';
            apellido_maternoEditar.value = data.success ? data.data.apellido_materno : '';
            cedulaEditar.value = data.success ? data.data.cedula : '';
            especialidadEditar.value = data.success ? data.data.especialidad : '';
            fecha_ingresoEditar.value = data.success ? data.data.fecha_ingreso: '';
        })
        .catch(error => console.error('Error:', error));

    // Evento de envío del formulario
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        var nuevoNombre = nombreEditar.value;
        var nuevoApellido_paterno = apellido_paternoEditar.value;
        var nuevoApellido_materno = apellido_maternoEditar.value;
        var nuevocedula = cedulaEditar.value;
        var nuevoespecialidad = especialidadEditar.value;
        var nuevofecha_ingreso = fecha_ingresoEditar.value;

        // Aquí puedes realizar una solicitud AJAX para actualizar los datos del usuario en el servidor
        fetch('actualizar_doctor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idDoc + '&nombre=' + encodeURIComponent(nuevoNombre) + '&apellido_paterno=' + encodeURIComponent(nuevoApellido_paterno) + '&apellido_materno=' + encodeURIComponent(nuevoApellido_materno) + '&cedula=' + encodeURIComponent(nuevocedula) + '&especialidad=' + encodeURIComponent(nuevoespecialidad) + '&fecha_ingreso=' + encodeURIComponent(nuevofecha_ingreso),
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
