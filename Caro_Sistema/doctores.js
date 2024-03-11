document.getElementById('doctorForm').addEventListener('submit', function (e) {
    e.preventDefault();

    var nombreDoctor = document.getElementById('nombreDoctor').value;
    var apellidoPaterDoc = document.getElementById('apellidoPaterDoc').value;
    var apellidoMaterDoc = document.getElementById('apellidoMaterDoc').value;
    var cedula = document.getElementById('cedula').value;
    var especialidad = document.getElementById('especialidad').value;
    var fechaIngresoDoctor = document.getElementById('fechaIngresoDoctor').value;

    // Validación simple (puedes agregar más validaciones según tus necesidades)
    if (nombreDoctor === '' || apellidoPaterDoc === '' || apellidoMaterDoc === '' || cedula === '' || especialidad === '' || fechaIngresoDoctor === '') {
        document.getElementById('mensaje').innerHTML = 'Por favor, completa todos los campos.';
        return;
    }

    // Aquí puedes hacer una solicitud AJAX para enviar los datos a PHP
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'agregar_doctores.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('mensaje').innerHTML = xhr.responseText;
            // Puedes realizar otras acciones después de enviar los datos a la base de datos

                        // Limpiar el formulario después de un envío exitoso
                        document.getElementById('doctorForm').reset();
        }
    };

    xhr.send('nombreDoctor=' + encodeURIComponent(nombreDoctor) + 
            '&apellidoPaterDoc=' + encodeURIComponent(apellidoPaterDoc) +
            '&apellidoMaterDoc=' + encodeURIComponent(apellidoMaterDoc) +
            '&cedula=' + encodeURIComponent(cedula) +
            '&especialidad=' + encodeURIComponent(especialidad) +
            '&fechaIngresoDoctor=' + encodeURIComponent(fechaIngresoDoctor));
});
