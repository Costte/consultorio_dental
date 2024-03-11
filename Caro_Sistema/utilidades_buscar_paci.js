document.addEventListener("DOMContentLoaded", function() {
    // Agregar un manejador de eventos para el envío del formulario
    document.getElementById("formBuscar2").addEventListener("submit", function(event) {
        // Evitar el comportamiento predeterminado del formulario (envío y recarga de la página)
        event.preventDefault();
        
        // Llamar a la función buscarCitaUtil() para manejar la búsqueda
        buscarPaciUtil();
    });
});

function buscarPaciUtil() {
    var bus_paci = document.getElementById("bus_paci").value;

    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4) {
            if (xhry.status == 200) {
                var usersx = JSON.parse(xhry.responseText);
                displayUsers(usersx);
                // Aquí puedes hacer algo con el ID recibido, por ejemplo, mostrarlo en la consola
                console.log("ID recibido:", usersx[0].id);
            } else {
                console.error("Error al realizar la solicitud. Estado de la solicitud: " + xhry.status);
            }
        }
    };
    xhry.open("POST", "utilidades_buscar_paci.php", true);
    xhry.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhry.send("bus_paci=" + encodeURIComponent(bus_paci));
}


function displayUsers(usersx) {
    console.log("Función displayUsers ejecutada correctamente.");
    console.log("Datos recibidos:", usersx);
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>IDs</th><th>Tipo</th><th>Fecha</th><th>Descripcion</th><th>Numero Cita</th><th>Paciente</th><th>Nombre Paciente</th><th>Numero Inventario</th><th>Producto</th><th>Monto</th>";

    for (var i = 0; i < usersx.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersx[i].id + "</td>";
        var estatus = usersx[i].tipo;
        var color = "";
        if (estatus == "Ingreso") {
            color = "#98FB98"; // Verde
        } else if (estatus == "Gasto") {
            color = "#e91c1cc2"; // Rojo
        }
        table2 += "<td style='background-color: " + color + "; font-weight: bold; color: white;'>" + estatus + "</td>";

        table2 += "<td>" + usersx[i].fecha + "</td>";
        table2 += "<td>" + usersx[i].descripcion + "</td>";
        table2 += "<td>" + usersx[i].id_cita + "</td>";
        table2 += "<td>" + usersx[i].id_paciente + "</td>";
        table2 += "<td id='nombre_paciente_" + usersx[i].id_paciente + "'></td>"; // Celda para mostrar el nombre del paciente
        table2 += "<td>" + usersx[i].id_inventario + "</td>";
        table2 += "<td>" + usersx[i].nombre_inventario + "</td>";
        table2 += "<td>" + usersx[i].monto + "</td>";
        table2 += "</tr>";
    }

    table2 += "</table>";

    // Mostrar la tabla en el elemento con id "resultados_citas"
    document.getElementById("resultados_gen").innerHTML = table2;

    // Agregar evento change al select de pacientes para obtener el nombre del paciente
    var idPacientes = usersx.map(user => user.id_paciente); // Obtener array de ids de pacientes
    idPacientes.forEach(idPaciente => {
        console.log("Solicitando nombre del paciente con ID:", idPaciente);
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obtener_nombre_paciente.php?id=" + idPaciente, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log("Respuesta del servidor:", xhr.responseText);
                    // Actualizar el campo de nombre del paciente con el nombre obtenido
                    document.getElementById("nombre_paciente_" + idPaciente).textContent = xhr.responseText;
                } else {
                    // Manejar errores si la solicitud no se completa correctamente
                    console.error("Error en la solicitud al servidor. Estado:", xhr.status);
                    console.error("Error al obtener el nombre del paciente. Estado de la solicitud: " + xhr.status);
                }
            }
        };
        xhr.send();
    });
}
