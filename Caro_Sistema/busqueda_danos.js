function result_danos(id_paciente, numero_diente) {
    // Realizar la solicitud fetch al archivo PHP
    fetch("busqueda_danos.php?id_paciente=" + id_paciente + "&numeroDiente=" + numero_diente)
        .then(response => {
            // Verificar si la respuesta es exitosa (código de estado 200)
            if (!response.ok) {
                throw new Error('Hubo un problema al obtener los datos.');
            }
            // Convertir la respuesta a JSON
            return response.json();
        })
        .then(datos => {
            // Una vez que se obtienen los datos, llamar a la función para mostrarlos en el modal
            displaybuscarDanos(datos);
        })
        .catch(error => {
            // Manejar errores
            console.error('Error:', error);
        });
}

function displaybuscarDanos(datos) {
    // Verificar si hay datos para mostrar
    if (datos.length > 0) {
        var table = "<table>";
        table += "<tr><th>Seccion</th><th>Tipo Daño</th><th>Ultima Modificacion</th></tr>";

        for (var i = 0; i < datos.length; i++) {
            table += "<tr>";
            table += "<td>" + datos[i].seccion + "</td>";
            table += "<td>" + datos[i].tipo_dano + "</td>";
            table += "<td>" + datos[i].fecha_dano + "</td>";
            // Agrega más columnas según sea necesario
            table += "</tr>";
        }

        table += "</table>";

        document.getElementById("resultados_danos").innerHTML = table;
    } else {
        // Mostrar un mensaje indicando que no hay datos para mostrar
        document.getElementById("resultados_danos").innerHTML = "<p>No hay datos para mostrar para este diente.</p>";
    }


}

// Obtener todos los elementos con la clase "diente"
var closeModalButton = document.querySelector('.close');

// Agregar un evento de clic al botón de cierre del modal para ocultar el modal
closeModalButton.addEventListener('click', function() {
    document.getElementById('myModal').style.display = 'none';
});

// Obtener todos los elementos con la clase "diente"
// Obtener todos los elementos con la clase "diente"
var dientes = document.querySelectorAll('.diente');

// Agregar un evento de clic a cada elemento de diente
dientes.forEach(function(diente) {
    diente.addEventListener('click', function() {
        // Obtener el ID del paciente
        var idPaciente = document.getElementById('id_paciente').textContent;
        // Obtener el número de diente del título del diente actual
        var numeroDiente = this.querySelector('.titulo_diente').textContent;
        
        // Obtener la sección del diente en el que se hizo clic
        var seccionDiente = this.getAttribute('id');
        
        // Extraer el número de sección del ID del diente
        var numeroSeccion = seccionDiente.split('_')[1];

        // Llamar a la función result_danos con los valores obtenidos
        result_danos(idPaciente, numeroSeccion);

        console.log("ID del paciente:", idPaciente);
        console.log("Número de diente:", numeroDiente);
    });
});

dientes.forEach(function(diente) {
    diente.addEventListener('click', function() {
        console.log("Se hizo clic en un diente.");
        // Resto del código...
    });
});

