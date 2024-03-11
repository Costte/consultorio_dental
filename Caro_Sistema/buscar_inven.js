//Busqueda general Doctores

function buscarInventario() {
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4 && xhry.status == 200) {
            var usersd = JSON.parse(xhry.responseText);
            displayUsers(usersd);
        }
    };
    xhry.open("GET", "busqueda_inventario.php", true);
    xhry.send();
}

function displayUsers(usersd) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>ID</th><th>Nombre</th><th>Cantidad</th><th>Costo Unidad</th><th>Costo Total</th><th>Cantidad Minima</th><th>Categoria</th><th>Ultima fecha actualizada</th><th>Editar</th><th>Borrar</th></tr>";

    for (var i = 0; i < usersd.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersd[i].id + "</td>";
        table2 += "<td>" + usersd[i].nombre + "</td>";
        table2 += "<td>" + usersd[i].cantidad + "</td>";
        table2 += "<td>" + usersd[i].costo_unitario + "</td>";
        table2 += "<td>" + usersd[i].valor_inventario + "</td>";
        table2 += "<td>" + usersd[i].cantidad_minima + "</td>";
        table2 += "<td>" + usersd[i].categoria + "</td>";
        table2 += "<td>" + usersd[i].fecha_actualizado + "</td>";
 
        table2 += "<td><button onclick='editarInventario(" + usersd[i].id + ")'>Editar</button></td>";
        table2 += "<td><button onclick='eliminarInventario(" + usersd[i].id + ")'>Eliminar</button></td>";
        table2 += "</tr>";
    }

    table2 += "</table>";

    // Mostrar la tabla en el elemento con id "userTable"
    document.getElementById("resultados_inv").innerHTML = table2;
}


// Función para eliminar un usuario
function eliminarInventario(idInven) {
    // Mostrar alerta para confirmar la eliminación
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el producto. ¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí puedes realizar una solicitud AJAX para eliminar el usuario en el servidor
            fetch('elimina_inventario.php?id=' + idInven, {
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
function editarInventario(idInven) {
    // Ejemplo de abrir un formulario en una nueva ventana (ajusta según tus necesidades)
    var formularioHTML = `
        <h2>Editar Producto</h2>
        <form id="formularioEditar">

            <label for="nombreEditar">Nombre:</label>
            <input type="text" id="nombreEditar" name="nombreEditar" required><br>

            <label for="cantidadEditar">Cantidad:</label>
            <input type="text" id="cantidadEditar" name="cantidadEditar" required><br>
            
            <label for="costo_unidadEditar">Costo Unidad:</label>
            <input type="text" id="costo_unidadEditar" name="costo_unidadEditar" required><br>

            <label for="valor_inventarioEditar">Valor Total:</label>
            <input type="text" id="valor_inventarioEditar" name="valor_inventarioEditar" readonly><br>

            <label for="cantidad_minimaEditar">Cantidad Minima:</label>
            <input type="text" id="cantidad_minimaEditar" name="cantidad_minimaEditar" required><br>
            
            <label for="categoriaEditar">Categoria:</label>
            <select id="categoriaEditar" name="categoriaEditar" required>
            <option value="Instrumentos y Herramientas">Instrumentos y Herramientas</option>
            <option value="Materiales dentales">Materiales dentales</option>
            <option value="Equipos y aparatos">Equipos y aparatos</option>
            <option value="Suministros de oficina<">Suministros de oficina</option>
            <option value="Suministros de higiene y limpieza">Suministros de higiene y limpieza</option>
            </select><br>  

            <label for="fechaEditar">Fecha:</label>
            <input type="date" id="fechaEditar" name="fechaEditar" readonly><br>

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
        function valorInventario() {
            var cantidaInventario = parseFloat(document.getElementById('cantidadEditar').value) || 0;
            var costoUnidad = parseFloat(document.getElementById('costo_unidadEditar').value) || 0;
            var costoTotal = cantidaInventario * costoUnidad;
            document.getElementById('valor_inventarioEditar').value = costoTotal;
        }
    
        // Agregar oyentes de eventos para los campos costo_tratamientoEditar y costo_consultaEditar
        document.getElementById('cantidadEditar').addEventListener('input', valorInventario);
        document.getElementById('costo_unidadEditar').addEventListener('input', valorInventario);
    
        // Calcular el costo total al cargar la página
        valorInventario();

    // Obtener el formulario y llenar los campos con los datos actuales del usuario
    var formulario = document.getElementById('formularioEditar');
    var nombreEditar = document.getElementById('nombreEditar');
    var cantidadEditar = document.getElementById('cantidadEditar');
    var costo_unidadEditar = document.getElementById('costo_unidadEditar');
    var valor_inventarioEditar = document.getElementById('valor_inventarioEditar');
    var cantidad_minimaEditar = document.getElementById('cantidad_minimaEditar');
    var categoriaEditar = document.getElementById('categoriaEditar');
    var fechaEditar = document.getElementById('fechaEditar');
  

    // Obtener los datos actuales del usuario para prellenar el formulario
    fetch('obtener_inventario.php?id=' + idInven)
        .then(response => response.json())
        .then(data => {
            console.log('Datos del usuario:', data); // Imprimir datos en la consola
            // Llenar los campos del formulario con los datos obtenidos
            nombreEditar.value = data.success ? data.data.nombre : '';
            cantidadEditar.value = data.success ? data.data.cantidad : '';
            costo_unidadEditar.value = data.success ? data.data.costo_unitario : '';
            valor_inventarioEditar.value = data.success ? data.data.valor_inventario : '';
            cantidad_minimaEditar.value = data.success ? data.data.cantidad_minima : '';
            categoriaEditar.value = data.success ? data.data.categoria : '';
            fechaEditar.value = data.success ? data.data.fecha_actualizado : '';

        })
        .catch(error => console.error('Error:', error));

    // Evento de envío del formulario
    formulario.addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        var nuevoNombre = nombreEditar.value;
        var nuevocantidad = cantidadEditar.value;
        var nuevocosto_unidad = costo_unidadEditar.value;
        var nuevovalor_inventario = valor_inventarioEditar.value;
        var nuevocantidad_minima = cantidad_minimaEditar.value;
        var nuevocategoria = categoriaEditar.value;
        var nuevofecha = fechaEditar.value;


        // Aquí puedes realizar una solicitud AJAX para actualizar los datos del usuario en el servidor
        fetch('actualizar_inventario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + idInven + '&nombre=' + encodeURIComponent(nuevoNombre) + '&cantidad=' + encodeURIComponent(nuevocantidad) + '&costo_unitario=' + encodeURIComponent(nuevocosto_unidad) + '&valor_inventario=' + encodeURIComponent(nuevovalor_inventario) + '&cantidad_minima=' + encodeURIComponent(nuevocantidad_minima) + '&categoria=' + encodeURIComponent(nuevocategoria) + '&fecha_actualizado=' + encodeURIComponent(nuevofecha),
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

