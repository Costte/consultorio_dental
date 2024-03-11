document.getElementById('trataForm').addEventListener('submit', function (e) {
    e.preventDefault();

    var nombreinve = document.getElementById('nombreinve').value;
    var cantidad = document.getElementById('cantidad').value;
    var costo = document.getElementById('costo').value;
    var cantida_min = document.getElementById('cantida_min').value;
    var categoria = document.getElementById('categoria').value;
    var fechaIngresoproducto = document.getElementById('fechaIngresoproducto').value;

    // Validación simple (puedes agregar más validaciones según tus necesidades)
    if (nombreinve === '' || cantidad === '' || costo === '' || cantida_min === '' || categoria === '' || fechaIngresoproducto === '') {
        document.getElementById('mensaje').innerHTML = 'Por favor, completa todos los campos.';
        return;
    }

    // Aquí puedes hacer una solicitud AJAX para enviar los datos a PHP
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'agregar_inventario.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('mensaje').innerHTML = xhr.responseText;
            // Puedes realizar otras acciones después de enviar los datos a la base de datos

                        // Limpiar el formulario después de un envío exitoso
                        document.getElementById('trataForm').reset();
        }
    };

    xhr.send('nombreinve=' + encodeURIComponent(nombreinve) + 
            '&cantidad=' + encodeURIComponent(cantidad) +
            '&costo=' + encodeURIComponent(costo) +
            '&cantida_min=' + encodeURIComponent(cantida_min) +
            '&categoria=' + encodeURIComponent(categoria) +
            '&fecha_actualizado=' + encodeURIComponent(fechaIngresoproducto));
});

   