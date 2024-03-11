document.addEventListener("DOMContentLoaded", function() {
    // Agregar un manejador de eventos para el envío del formulario
    document.getElementById("formBuscar22").addEventListener("submit", function(event) {
        // Evitar el comportamiento predeterminado del formulario (envío y recarga de la página)
        event.preventDefault();
        
        // Llamar a la función buscarCitaUtil() para manejar la búsqueda
        buscarInveUtil();
    });
});

function buscarInveUtil() {
    var bus_inve = document.getElementById("bus_inve").value;

    // Realizar la solicitud AJAX para obtener los usuarios
    var xhry = new XMLHttpRequest();
    xhry.onreadystatechange = function () {
        if (xhry.readyState == 4) {
            if (xhry.status == 200) {
                var usersh = JSON.parse(xhry.responseText);
                displayUsers(usersh);
                // Aquí puedes hacer algo con el ID recibido, por ejemplo, mostrarlo en la consola
                console.log("ID recibido:", usersh[0].id);
            } else {
                console.error("Error al realizar la solicitud. Estado de la solicitud: " + xhry.status);
            }
        }
    };
    xhry.open("POST", "utilidades_buscar_prod.php", true);
    xhry.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhry.send("bus_inve=" + encodeURIComponent(bus_inve));
}


function displayUsers(usersh) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general'>";
    table2 += "<tr><th>ID</th><th>Tipo</th><th>Fecha</th><th>Descripcion</th><th>Numero Cita</th><th>Paciente</th><th>Numero Inventario</th><th>Producto</th><th>Monto</th>";
    
    for (var i = 0; i < usersh.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersh[i].id + "</td>";
        var estatus = usersh[i].tipo;
        var color = "";
        if (estatus == "Ingreso") {
            color = "#98FB98"; // Verde
        } else if (estatus == "Gasto") {
            color = "#e91c1cc2"; // Rojo
        } 
        table2 += "<td style='background-color: " + color + "; font-weight: bold; color: white;'>" + estatus + "</td>";

        table2 += "<td>" + usersh[i].fecha + "</td>";
        table2 += "<td>" + usersh[i].descripcion + "</td>";
        table2 += "<td>" + usersh[i].id_cita + "</td>";
        table2 += "<td>" + usersh[i].id_paciente + "</td>";
        table2 += "<td>" + usersh[i].id_inventario + "</td>";
        table2 += "<td>" + usersh[i].nombre_inventario + "</td>";
        table2 += "<td>" + usersh[i].monto + "</td>";
        table2 += "</tr>";
    }
    
    table2 += "</table>";

    // Mostrar la tabla en el elemento con id "resultados_citas"
    document.getElementById("resultados_gen").innerHTML = table2;

}
