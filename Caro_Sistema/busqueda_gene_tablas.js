document.addEventListener("DOMContentLoaded", function() {
    buscarDeudas(); // Llamada a la función buscarCitasDeudas() al cargar la página
    buscarCitas(); // Llamada a la función buscarCitasHoy() al cargar la página
});

function buscarDeudas() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var usersd = JSON.parse(xhr.responseText);
            displayUsersDeudas(usersd);
        }
    };
    xhr.open("GET", "busqueda_gen_citap.php", true);
    xhr.send();
}

function buscarCitas() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var usersh = JSON.parse(xhr.responseText);
            displayUsersHoy(usersh);
        }
    };
    xhr.open("GET", "busqueda_gen_deuda.php", true);
    xhr.send();
}

function displayUsersDeudas(usersd) {
  // Crear la tabla HTML con los usuarios
  var table2 = "<table class='result_general tabla_citasp'>";
  table2 += "<tr><th>Numero de Cita</th><th>Descripcion</th><th>Paciente</th><th>Fecha de la cita</th><th>Estatus cita</th></tr>";
  
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

function displayUsersHoy(usersh) {
    // Lógica para mostrar resultados de citas de hoy
 
    var table2 = "<table class='result_deudad result_deudad'>";
    table2 += "<tr><th>Numero de Cita</th><th>Descripcion</th><th>Paciente</th><th>Fecha de la cita</th><th>Costo Total</th><th>Pagado</th><th>Deuda Pendiente</th></tr>";
    
    for (var i = 0; i < usersh.length; i++) {
        table2 += "<tr>";
        table2 += "<td hidden>" + usersh[i].id + "</td>";
        table2 += "<td>" + usersh[i].codigo_eventos + "</td>";
        table2 += "<td>" + usersh[i].descripcion_cita + "</td>";
        table2 += "<td id='nombre_paciente" + usersh[i].id_paciente + "'></td>"; // Placeholder para el nombre del paciente
        table2 += "<td>" + usersh[i].fecha + "</td>";
        table2 += "<td>" + usersh[i].costo_total + "</td>";
        table2 += "<td>" + usersh[i].costo_pagado + "</td>";
        table2 += "<td style= color:red; font-weight: bold; >" + usersh[i].total_deuda + "</td>";
    
        table2 += "</tr>";
    }
    
    table2 += "</table>";
    

    // Mostrar la tabla en el elemento con id "resultados_citas"
    document.getElementById("resultados_deudas").innerHTML = table2;

        // Agregar evento change al select de pacientes para obtener el nombre del paciente
        var idPacientes = usersh.map(user => user.id_paciente); // Obtener array de ids de pacientes
        idPacientes.forEach(idPaciente => {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "obtener_nombre_paciente.php?id=" + idPaciente, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Actualizar el campo de nombre del paciente con el nombre obtenido
                        document.getElementById("nombre_paciente" + idPaciente).textContent = xhr.responseText;
                    } else {
                        // Manejar errores si la solicitud no se completa correctamente
                        console.error("Error al obtener el nombre del paciente. Estado de la solicitud: " + xhr.status);
                    }
                }
            };
            xhr.send();
        });
    }
   


