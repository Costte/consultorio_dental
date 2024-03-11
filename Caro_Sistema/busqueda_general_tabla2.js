document.addEventListener("DOMContentLoaded", function() {
    buscarIngresos(); // Llamada a la función buscarIngresos() al cargar la página
    buscarGastos(); // Llamada a la función buscarGastos() al cargar la página
});

function buscarIngresos() {
    fetch("busqueda_ingresos.php")
        .then(response => response.json())
        .then(data => displayUsersIngresos(data))
        .catch(error => console.error("Error al buscar ingresos:", error));
}

function buscarGastos() {
    fetch("busqueda_gastos.php")
        .then(response => response.json())
        .then(data => displayUsersGastos(data))
        .catch(error => console.error("Error al buscar gastos:", error));
}

function displayUsersIngresos(usersd) {
    // Crear la tabla HTML con los usuarios
    var table2 = "<table class='result_general tabla_citasp'>";
    table2 += "<tr><th>Fecha</th><th>Descripción</th><th>Paciente</th><th>Id Cita</th><th>Monto</th></tr>";
    
    for (var i = 0; i < usersd.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersd[i].fecha + "</td>";
        table2 += "<td>" + usersd[i].descripcion + "</td>";
        table2 += "<td class='nombre_paciente' data-id='" + usersd[i].id_paciente + "'></td>"; // Utilizamos data-id para guardar el id del paciente
        table2 += "<td>" + usersd[i].id_cita + "</td>";
        table2 += "<td style='color:green; font-weight: bold;'>" + usersd[i].monto + "</td>";
        table2 += "</tr>";
    }
    
    table2 += "</table>";
    
    // Mostrar la tabla en el elemento con id "resultados_ingresos"
    document.getElementById("resultados_ingresos").innerHTML = table2;

    // Agregar evento change al select de pacientes para obtener el nombre del paciente
    document.querySelectorAll('.nombre_paciente').forEach(element => {
        var idPaciente = element.getAttribute('data-id');
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obtener_nombre_paciente.php?id=" + idPaciente, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Actualizar el campo de nombre del paciente con el nombre obtenido
                    element.textContent = xhr.responseText;
                } else {
                    // Manejar errores si la solicitud no se completa correctamente
                    console.error("Error al obtener el nombre del paciente. Estado de la solicitud: " + xhr.status);
                }
            }
        };
        xhr.send();
    });
}

function displayUsersGastos(usersh) {
    // Lógica para mostrar resultados de citas de hoy
 
    var table2 = "<table class='result_general tabla_citasp'>";
    table2 += "<tr><th>Fecha</th><th>Descripcion</th><th>Producto</th><th>Id Producto</th><th>Monto</th></tr>";
    
    for (var i = 0; i < usersh.length; i++) {
        table2 += "<tr>";
        table2 += "<td>" + usersh[i].fecha + "</td>";
        table2 += "<td>" + usersh[i].descripcion + "</td>";
        table2 += "<td>" + usersh[i].nombre_inventario + "</td>";
        table2 += "<td>" + usersh[i].id_inventario + "</td>";
        table2 += "<td style= color:red; font-weight: bold;>" + usersh[i].monto + "</td>";
    
        table2 += "</tr>";
    }
    
    table2 += "</table>";
    

    // Mostrar la tabla en el elemento con id "resultados_citas"
    document.getElementById("resultados_gastos").innerHTML = table2;

    }