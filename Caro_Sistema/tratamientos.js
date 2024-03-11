document.getElementById('trataForm').addEventListener('submit', function (e) {
    e.preventDefault();

    var nombretrata = document.getElementById('nombretrata').value;
    var preciotrata = document.getElementById('preciotrata').value;

    // Validación simple (puedes agregar más validaciones según tus necesidades)
    if (nombretrata === '' || preciotrata === '') {
        document.getElementById('mensaje').innerHTML = 'Por favor, completa todos los campos.';
        return;
    }

    // Aquí puedes hacer una solicitud AJAX para enviar los datos a PHP
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'agregar_tratamiento.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('mensaje').innerHTML = xhr.responseText;
            // Puedes realizar otras acciones después de enviar los datos a la base de datos

                        // Limpiar el formulario después de un envío exitoso
                        document.getElementById('trataForm').reset();
        }
    };

    xhr.send('nombretrata=' + encodeURIComponent(nombretrata) + 
            '&preciotrata=' + encodeURIComponent(preciotrata));
});
