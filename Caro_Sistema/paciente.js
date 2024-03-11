document.getElementById('pacienteForm').addEventListener('submit', function (e) {
    e.preventDefault();

    var nombrePaciente = document.getElementById('nombrePaciente').value;
    var apellidoPater = document.getElementById('apellidoPater').value;
    var apellidoMater = document.getElementById('apellidoMater').value;
    var fechanacimientoPaciente = document.getElementById('fechanacimientoPaciente').value;
    var edadPaciente = document.getElementById('edadPaciente').value;
    var telefonoPaciente = document.getElementById('telefonoPaciente').value;
    var emailPaciente = document.getElementById('emailPaciente').value;
    var sexoPaciente = document.getElementById('sexoPaciente').value;
    var fechaIngresoPaciente = document.getElementById('fechaIngresoPaciente').value;
    var alergiasPaciente = document.getElementById('alergiasPaciente').value;

    // Validación simple (puedes agregar más validaciones según tus necesidades)
    if (nombrePaciente === '' || apellidoPater === '' || apellidoMater === '' || fechanacimientoPaciente === '' || edadPaciente === '' || telefonoPaciente === '' || sexoPaciente === '' || fechaIngresoPaciente === '' || alergiasPaciente === '') {
        document.getElementById('mensaje').innerHTML = 'Por favor, completa todos los campos.';
        return;
    }

    // Aquí puedes hacer una solicitud AJAX para enviar los datos a PHP
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'agregar_paciente.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('mensaje').innerHTML = xhr.responseText;
            // Puedes realizar otras acciones después de enviar los datos a la base de datos

                        // Limpiar el formulario después de un envío exitoso
                        document.getElementById('pacienteForm').reset();
        }
    };

    xhr.send('nombrePaciente=' + encodeURIComponent(nombrePaciente) + 
            '&apellidoPater=' + encodeURIComponent(apellidoPater) +
            '&apellidoMater=' + encodeURIComponent(apellidoMater) +
            '&fechanacimientoPaciente=' + encodeURIComponent(fechanacimientoPaciente) +
            '&edadPaciente=' + encodeURIComponent(edadPaciente) +
            '&telefonoPaciente=' + encodeURIComponent(telefonoPaciente) +
            '&emailPaciente=' + encodeURIComponent(emailPaciente) +
            '&sexoPaciente=' + encodeURIComponent(sexoPaciente) +
            '&fechaIngresoPaciente=' + encodeURIComponent(fechaIngresoPaciente) +
            '&alergiasPaciente=' + encodeURIComponent(alergiasPaciente));
});
