function guardar() {
    const idPaciente = document.getElementById('id_paciente').textContent;
    const numeroDiente = document.getElementById('numeroDiente').value;
    const seccion = document.getElementById('seccion_dano').value;
    const numero_sec = document.getElementById('numero_sec').textContent;
    const tipoDano = document.getElementById('tipoDano').value;

    // Mostrar el ID del paciente en el modal
    document.getElementById('id_paciente').textContent = idPaciente;

    // Enviar los datos al servidor
    fetch('guardar_danos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_paciente=${idPaciente}&numero_diente=${numeroDiente}&seccion=${seccion}&numero_sec=${numero_sec}&tipo_dano=${tipoDano}`,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al guardar los datos');
        }
        return response.text();
    })
    .then(data => {
        console.log(data); // Aquí puedes manejar la respuesta del servidor
        cerrarModal(); // Cierra el modal después de guardar los datos
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
