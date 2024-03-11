//Busqueda general Doctores

function buscarTratamientos() {
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4 && xhry.status == 200) {
            var usersd = JSON.parse(xhry.responseText);
            displayUsers(usersd);
        }
    };
    xhry.open("GET", "busqueda_tratamiento.php", true);
    xhry.send();
}

function displayUsers(usersd) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Editar</th><th>Eliminar</th></tr>";

    for (var i = 0; i < usersd.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersd[i].id + "</td>";
        table2 += "<td>" + usersd[i].nombre + "</td>";
        table2 += "<td>" + usersd[i].costo_tratamiento + "</td>";
 
        table2 += "<td><button onclick='editarTratamiento(" + usersd[i].id + ")'>Editar</button></td>";
        table2 += "<td><button onclick='eliminarTratamiento(" + usersd[i].id + ")'>Eliminar</button></td>";
        table2 += "</tr>";
    }

    table2 += "</table>";

    // Mostrar la tabla en el elemento con id "userTable"
    document.getElementById("resultados_doc").innerHTML = table2;
}


// Función para eliminar un usuario
function eliminarTratamiento(idTrat) {
    // Mostrar alerta para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el tratamiento. ¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí puedes realizar una solicitud AJAX para eliminar el usuario en el servidor
            fetch('elimina_tratamiento.php?id=' + idTrat, {
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
function editarTratamiento(idTrat) {
    // Ejemplo de abrir un formulario en una nueva ventana (ajusta según tus necesidades)
    var formularioHTML = `
        <h2>Editar Tratamiento</h2>
        <form id="formularioEditar">

            <label for="nombreEditar">Nombre:</label>
            <input type="text" id="nombreEditar" name="nombreEditar" required><br>

            <label for="costo_tratamientoEditar">Precio:</label>
            <input type="text" id="costo_tratamientoEditar" name="costo_tratamientoEditar" required><br>
            
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
    var costo_tratamientoEditar = document.getElementById('costo_tratamientoEditar');
  

    // Obtener los datos actuales del usuario para prellenar el formulario
    fetch('obtener_tratamiento.php?id=' + idTrat)
        .then(response => response.json())
        .then(data => {
            console.log('Datos del usuario:', data); // Imprimir datos en la consola
            // Llenar los campos del formulario con los datos obtenidos
            nombreEditar.value = data.success ? data.data.nombre : '';
            costo_tratamientoEditar.value = data.success ? data.data.costo_tratamiento : '';

        })
        .catch(error => console.error('Error:', error));

    // Evento de envío del formulario
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        var nuevoNombre = nombreEditar.value;
        var nuevocosto_tratamiento = costo_tratamientoEditar.value;


        // Aquí puedes realizar una solicitud AJAX para actualizar los datos del usuario en el servidor
        fetch('actualizar_tratamiento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idTrat + '&nombre=' + encodeURIComponent(nuevoNombre) + '&costo_tratamiento=' + encodeURIComponent(nuevocosto_tratamiento),
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
