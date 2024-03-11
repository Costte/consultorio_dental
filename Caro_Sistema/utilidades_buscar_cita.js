document.addEventListener("DOMContentLoaded", function() {
    // Agregar un manejador de eventos para el envío del formulario
    document.getElementById("formBuscar").addEventListener("submit", function(event) {
        // Evitar el comportamiento predeterminado del formulario (envío y recarga de la página)
        event.preventDefault();
        
        // Llamar a la función buscarCitaUtil() para manejar la búsqueda
        buscarCitaUtil();
    });
});

function buscarCitaUtil() {
    var bus_cita = document.getElementById("bus_cita").value;
    
    // Realizar la solicitud AJAX para obtener los usuarios
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var usersd = JSON.parse(xhr.responseText);
            displayUsers(usersd);
        }
    };
    xhr.open("POST", "utilidades_buscar_cita2.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("bus_cita=" + encodeURIComponent(bus_cita));
}

function displayUsers(usersd) {
    // Crear la tabla HTML con los usuarios
    var table = "<table>";
    table += "<tr><th>ID</th><th>Tipo</th><th>Fecha</th><th>Descripcion</th><th>Numero Cita</th><th>Paciente</th><th>Paciente2</th><th>Numero Inventario</th><th>Producto</th><th>Monto</th>";
    
    for (var i = 0; i < usersd.length; i++) {
        table += "<tr>";
        table += "<td>" + usersd[i].id + "</td>";
        var estatus = usersd[i].tipo;
        var color = "";
        if (estatus == "Ingreso") {
            color = "#98FB98"; // Verde
        } else if (estatus == "Gasto") {
            color = "#e91c1c"; // Rojo
        } 
        table += "<td style='background-color: " + color + "; font-weight: bold; color: white;'>" + estatus + "</td>";

        table += "<td>" + usersd[i].fecha + "</td>";
        table += "<td>" + usersd[i].descripcion + "</td>";
        table += "<td>" + usersd[i].id_cita + "</td>";
        table += "<td>" + usersd[i].id_paciente + "</td>";
        table += "<td>" + usersd[i].nombre_paciente + "</td>";
        table += "<td>" + usersd[i].id_inventario + "</td>";
        table += "<td>" + usersd[i].nombre_inventario + "</td>";
        table += "<td>" + usersd[i].monto + "</td>";
        table += "</tr>";
    }
    
    table += "</table>";

    // Mostrar la tabla en el elemento con id "resultados_gen"
    document.getElementById("resultados_gen").innerHTML = table;
}
