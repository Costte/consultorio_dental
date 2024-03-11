document.addEventListener('DOMContentLoaded', function() {
  // Agregar evento de clic a cada diente
  document.querySelectorAll('.diente').forEach(diente => {
    diente.addEventListener('click', mostrarModal);
  });

  // Agregar evento de clic a cada sección
  document.querySelectorAll('.seccion').forEach(seccion => {
    seccion.addEventListener('click', mostrarSeccion);
  });

  // Cerrar el modal al hacer clic en la X
  document.querySelector('.close').addEventListener('click', cerrarModal);

});

function mostrarSeccion(event) {
  const seccionSeleccionada = event.currentTarget;
  const nombreSeccion = seccionSeleccionada.getAttribute('data-seccion');

  // Actualizar el campo de sección en el modal con el nombre de la sección seleccionada
  document.getElementById('seccion_dano').value = nombreSeccion;

  // Obtener el ID del div que se le dio clic
  const idDiv = seccionSeleccionada.id;

  // Obtener el número de sección del ID del div
  const numeroSeccion = idDiv.split('_')[0].slice(1); // Eliminando el primer carácter "q" para obtener solo el número

  // Actualizar el campo de número de sección en el modal
  document.getElementById('numero_sec').textContent = numeroSeccion;

  // Mostrar el modal
  document.getElementById('myModal').style.display = 'block';
}

function mostrarModal(event) {
  const dienteSeleccionado = event.currentTarget;
  const idDiente = dienteSeleccionado.id;
  const numeroDiente = idDiente.split('_')[1];
  const seccion = idDiente.split('_')[0];
  
  // Asignar automáticamente el número de diente al input correspondiente
  document.getElementById('numeroDiente').value = numeroDiente;

  // Mostrar el modal
  document.getElementById('myModal').style.display = 'block';
}

function cerrarModal() {
  // Cerrar el modal
  document.getElementById('myModal').style.display = 'none';
}

function guardar() {
  // Obtener el valor seleccionado del tipo de daño
  const tipoDanoSeleccionado = document.getElementById('tipoDano').value;

  // Agregar aquí la lógica para guardar la información del tipo de daño si es necesario
  
  // Cerrar el modal después de guardar la información
  cerrarModal();
}
