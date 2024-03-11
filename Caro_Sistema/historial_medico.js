function historialMe(idUsuario) {
    // Realizar la solicitud AJAX para obtener el historial médico del paciente
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var paciente = JSON.parse(xhr.responseText);
            displayHistorialMedico(paciente, idUsuario); // Pasar idUsuario a displayHistorialMedico
        }
    };
    xhr.open("GET", "historial_medico.php?id=" + idUsuario, true);
    xhr.send();
}

function displayHistorialMedico(paciente, idUsuario) {
    // Obtener el ID del médico asignado al paciente
    var idDoctor = paciente.id_doctor;

    // Crear la tabla HTML con el historial médico del paciente
    var tabla = "<table class='histo_medico_tabla'>";
    tabla += "<tr><th colspan='4'>Historial médico de " + paciente.nombre + "</th></tr>";
    tabla += "<tr><td style='color: MediumSlateBlue; font-weight: bold;'>Nombre Completo:</td><td>" + paciente.nombre + " " + paciente.apellido_paterno + " " + paciente.apellido_materno + "</td>";
    tabla += "<td style='color: MediumSlateBlue; font-weight: bold;'>Edad:</td><td>" + paciente.edad + "</td></tr>";
    tabla += "<tr><td style='color: MediumSlateBlue; font-weight: bold;'>Fecha de Nacimiento:</td><td>" + paciente.fecha_nacimiento + "</td>";
    tabla += "<td style='color: MediumSlateBlue; font-weight: bold;'>Fecha de Ingreso:</td><td>" + paciente.fecha_ingreso + "</td></tr>";
    tabla += "<tr><td style='color: MediumSlateBlue; font-weight: bold;'>Sexo:</td><td>" + paciente.sexo + "</td>";

    // Realizar una nueva solicitud AJAX para obtener la información del doctor
    var xhrDoctor = new XMLHttpRequest();
    xhrDoctor.onreadystatechange = function () {
        if (xhrDoctor.readyState == 4 && xhrDoctor.status == 200) {
            var doctor = JSON.parse(xhrDoctor.responseText);
            tabla += "<td style='color: MediumSlateBlue; font-weight: bold;'>Doctor Asignado:</td><td>" + doctor.nombre + " " + doctor.apellido_paterno + " " + doctor.apellido_materno + "</td></tr>";

            
            // Mostrar la tabla después de obtener la información del doctor
            mostrarHistorialMedicoYTablaCitas(idUsuario, tabla); // Pasar idUsuario a mostrarHistorialMedicoYTablaCitas
        }
    };
    xhrDoctor.open("GET", "obtener_nombre_doctor.php?id=" + idDoctor, true);
    xhrDoctor.send();
}

function mostrarHistorialMedicoYTablaCitas(idUsuario, tablaHistorial) {
    // Realizar solicitud AJAX para obtener las citas del paciente
    var xhrCitas = new XMLHttpRequest();
    xhrCitas.onreadystatechange = function () {
        if (xhrCitas.readyState == 4 && xhrCitas.status == 200) {
            var citas = JSON.parse(xhrCitas.responseText);

            // Realizar solicitud AJAX para obtener los tratamientos
            var xhrTratamientos = new XMLHttpRequest();
            xhrTratamientos.onreadystatechange = function () {
                if (xhrTratamientos.readyState == 4 && xhrTratamientos.status == 200) {
                    var tratamientos = JSON.parse(xhrTratamientos.responseText);
                    var tabla = tablaHistorial; // Utilizar la tabla del historial médico ya creada

                    tabla += "<tr><td colspan='4'><b>Historial de Citas:</b></td></tr>";
                    tabla += "<tr><th>Fecha:</th><th>Descripción:</th><th>Tratamiento:</th><th>Comentarios:</th></tr>";
                    citas.forEach(function(cita) {
                        // Buscar el nombre del tratamiento correspondiente al ID de tratamiento en la cita
                        var nombreTratamiento = "";
                        tratamientos.forEach(function(tratamiento) {
                            if (tratamiento.id == cita.id_tratamiento) {
                                nombreTratamiento = tratamiento.nombre;
                            }
                        });

                        tabla += "<tr><td>" + cita.fecha + "</td><td>" + cita.descripcion_cita + "</td> <td>" + nombreTratamiento + "</td> <td><textarea style='color: MediumVioletRed; font-weight: bold;' id='comentarios_" + cita.id + "' class='comentario-textarea'>" + cita.comentarios + "</textarea></td></tr>";
                    });
                                                    // Agregar el odontograma al final de la tabla
            tabla += "<tr><td colspan='4'>" + obtenerOdontogramaHTML() + "</td></tr>";
                    tabla += "</table>";
                    
                    // Crear un contenedor div para la tabla y mostrarla en un modal o ventana emergente
                    Swal.fire({
                        title: 'Historial Médico y Citas',
                        html: '<div class="tabla-container">' + tabla + '</div>',
                        showCancelButton: false,
                        showConfirmButton: false
                    });



                    // Agregar event listener para guardar los comentarios cuando se cambia el valor del textarea
                    document.querySelectorAll('.comentario-textarea').forEach(function(textarea) {
                        textarea.addEventListener('change', function() {
                            guardarComentario(textarea.id.split('_')[1], textarea.value);
                        });
                    });
                }
            };
            xhrTratamientos.open("GET", "obtener_tratamientos22.php", true); // URL para obtener tratamientos
            xhrTratamientos.send();
        }
    };
    xhrCitas.open("GET", "obtener_citas_histo.php?id_paciente=" + idUsuario, true); // Corregir la URL de la solicitud AJAX
    xhrCitas.send();
}

// Función para guardar el comentario actualizado
function guardarComentario(idCita, comentario) {
    // Realizar solicitud AJAX para guardar el comentario actualizado en la base de datos
    var xhrGuardarComentario = new XMLHttpRequest();
    xhrGuardarComentario.onreadystatechange = function () {
        if (xhrGuardarComentario.readyState == 4 && xhrGuardarComentario.status == 200) {
            // Mostrar mensaje de éxito o manejar cualquier otro resultado si es necesario
            console.log("Comentario guardado correctamente.");
        }
    };
    xhrGuardarComentario.open("POST", "guardar_comentario.php", true); // URL para guardar el comentario
    xhrGuardarComentario.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhrGuardarComentario.send("id_cita=" + idCita + "&comentario=" + encodeURIComponent(comentario));
}



function obtenerOdontogramaHTML() {
    // Aquí va el código HTML del odontograma
    var odontogramaHTML = `

 
    <h4>Odontograma del paciente</h4>

    <h2>Seleccionar sección del odontograma:</h2>
  <form id="odontogramaForm" class="buscador_form_gin ">
  <div class="input_group">    
  <label for="cuadrante" class="label" class="input">Cuadrante:</label>
  <select id="cuadrante">
    <option value="1">Superior</option>
    <option value="2">Inferior</option>
  </select>
  </div>

<div class="input_group">
<label for="diente" class="label" class="input">Diente:</label>
    <select id="diente">
      <!-- Opciones de dientes generadas por JavaScript -->
    </select>
</div>
    

    <div class="input_group">
    <label for="seccion" class="label" class="input">Sección:</label>
    <select id="seccion">
      <!-- Opciones de secciones generadas por JavaScript -->
    </select>
    </div>
    

    <div class="input_group">
    <label for="damage" class="label" class="input">Tipo de daño:</label>
    <select id="damage">
      <option value="Presente">Presente</option>
      <option value="Ausente">Ausente</option>
      <option value="Cariado">Cariado</option>
      <option value="Tratado">Tratado</option>
    </select>
    </div>
 

    <div class="input_group">

    <button type="submit" value="Marcar sección">Asignar</button>
    </div>
    
  </form>

  <div class="odontograma">
  <div class="cuadrante_sup">
    <div class="cuadrante" id="cuadrante_1">
      <div class="diente" id="diente_18">
        <div class="seccion q1" id="q1_18"></div>
        <div class="seccion q2" id="q2_18"></div>
        <div class="seccion q3" id="q3_18"></div>
        <div class="seccion q4" id="q4_18"></div>
        <div class="seccion q5" id="q5_18"></div>
        <div class="titulo_diente">18</div>
      </div>
      <div class="diente" id="diente_17">
        <div class="seccion q1" id="q1_17"></div>
        <div class="seccion q2" id="q2_17"></div>
        <div class="seccion q3" id="q3_17"></div>
        <div class="seccion q4" id="q4_17"></div>
        <div class="seccion q5" id="q5_17"></div>
        <div class="titulo_diente">17</div>
      </div>
      <div class="diente" id="diente_16">
        <div class="seccion q1" id="q1_16"></div>
        <div class="seccion q2" id="q2_16"></div>
        <div class="seccion q3" id="q3_16"></div>
        <div class="seccion q4" id="q4_16"></div>
        <div class="seccion q5" id="q5_16"></div>
        <div class="titulo_diente">16</div>
      </div>
      <div class="diente" id="diente_15">
        <div class="seccion q1" id="q1_15"></div>
        <div class="seccion q2" id="q2_15"></div>
        <div class="seccion q3" id="q3_15"></div>
        <div class="seccion q4" id="q4_15"></div>
        <div class="seccion q5" id="q5_15"></div>
        <div class="titulo_diente">15</div>
      </div>
      <div class="diente" id="diente_14">
        <div class="seccion q1" id="q1_14"></div>
        <div class="seccion q2" id="q2_14"></div>
        <div class="seccion q3" id="q3_14"></div>
        <div class="seccion q4" id="q4_14"></div>
        <div class="seccion q5" id="q5_14"></div>
        <div class="titulo_diente">14</div>
      </div>
      <div class="diente" id="diente_13">
        <div class="seccion q1" id="q1_13"></div>
        <div class="seccion q2" id="q2_13"></div>
        <div class="seccion q3" id="q3_13"></div>
        <div class="seccion q4" id="q4_13"></div>
        <div class="seccion q5" id="q5_13"></div>
        <div class="titulo_diente">13</div>
      </div>
      <div class="diente" id="diente_12">
        <div class="seccion q1" id="q1_12"></div>
        <div class="seccion q2" id="q2_12"></div>
        <div class="seccion q3" id="q3_12"></div>
        <div class="seccion q4" id="q4_12"></div>
        <div class="seccion q5" id="q5_12"></div>
        <div class="titulo_diente">12</div>
      </div>
      <div class="diente" id="diente_11">
        <div class="seccion q1" id="q1_11"></div>
        <div class="seccion q2" id="q2_11"></div>
        <div class="seccion q3" id="q3_11"></div>
        <div class="seccion q4" id="q4_11"></div>
        <div class="seccion q5" id="q5_11"></div>
        <div class="titulo_diente">11</div>
      </div>
    </div>
    <div class="cuadrante" id="cuadrante_2">
      <div class="diente" id="diente_21">
        <div class="seccion q1" id="q1_21"></div>
        <div class="seccion q2" id="q2_21"></div>
        <div class="seccion q3" id="q3_21"></div>
        <div class="seccion q4" id="q4_21"></div>
        <div class="seccion q5" id="q5_21"></div>
        <div class="titulo_diente">21</div>
      </div>
      <div class="diente" id="diente_22">
        <div class="seccion q1" id="q1_22"></div>
        <div class="seccion q2" id="q2_22"></div>
        <div class="seccion q3" id="q3_22"></div>
        <div class="seccion q4" id="q4_22"></div>
        <div class="seccion q5" id="q5_22"></div>
        <div class="titulo_diente">22</div>
      </div>
      <div class="diente" id="diente_23">
        <div class="seccion q1" id="q1_23"></div>
        <div class="seccion q2" id="q2_23"></div>
        <div class="seccion q3" id="q3_23"></div>
        <div class="seccion q4" id="q4_23"></div>
        <div class="seccion q5" id="q5_23"></div>
        <div class="titulo_diente">23</div>
      </div>
      <div class="diente" id="diente_24">
        <div class="seccion q1" id="q1_24"></div>
        <div class="seccion q2" id="q2_24"></div>
        <div class="seccion q3" id="q3_24"></div>
        <div class="seccion q4" id="q4_24"></div>
        <div class="seccion q5" id="q5_24"></div>
        <div class="titulo_diente">24</div>
      </div>
      <div class="diente" id="diente_25">
        <div class="seccion q1" id="q1_25"></div>
        <div class="seccion q2" id="q2_25"></div>
        <div class="seccion q3" id="q3_25"></div>
        <div class="seccion q4" id="q4_25"></div>
        <div class="seccion q5" id="q5_25"></div>
        <div class="titulo_diente">25</div>
      </div>
      <div class="diente" id="diente_26">
        <div class="seccion q1" id="q1_26"></div>
        <div class="seccion q2" id="q2_26"></div>
        <div class="seccion q3" id="q3_26"></div>
        <div class="seccion q4" id="q4_26"></div>
        <div class="seccion q5" id="q5_26"></div>
        <div class="titulo_diente">26</div>
      </div>
      <div class="diente" id="diente_27">
        <div class="seccion q1" id="q1_27"></div>
        <div class="seccion q2" id="q2_27"></div>
        <div class="seccion q3" id="q3_27"></div>
        <div class="seccion q4" id="q4_27"></div>
        <div class="seccion q5" id="q5_27"></div>
        <div class="titulo_diente">27</div>
      </div>
      <div class="diente" id="diente_28">
        <div class="seccion q1" id="q1_28"></div>
        <div class="seccion q2" id="q2_28"></div>
        <div class="seccion q3" id="q3_28"></div>
        <div class="seccion q4" id="q4_28"></div>
        <div class="seccion q5" id="q5_28"></div>
        <div class="titulo_diente">28</div>
      </div>
    </div>
  </div>
  
  <div class="cuandrantes_int">
  <div class="cuadrante_sup_int">
    <div class="cuadrante" id="cuadranteInt_1">
      <div class="diente" id="diente_55">
        <div class="seccion q1" id="q1_55"></div>
        <div class="seccion q2" id="q2_55"></div>
        <div class="seccion q3" id="q3_55"></div>
        <div class="seccion q4" id="q4_55"></div>
        <div class="seccion q5" id="q5_55"><</div>
        <div class="titulo_diente">55</div>
      </div>
      <div class="diente" id="diente_54">
        <div class="seccion q1" id="q1_54"></div>
        <div class="seccion q2" id="q2_54"></div>
        <div class="seccion q3" id="q3_54"></div>
        <div class="seccion q4" id="q4_54"></div>
        <div class="seccion q5" id="q5_54"></div>
        <div class="titulo_diente">54</div>
      </div>
      <div class="diente" id="diente_53">
        <div class="seccion q1" id="q1_53"></div>
        <div class="seccion q2" id="q2_53"></div>
        <div class="seccion q3" id="q3_53"></div>
        <div class="seccion q4" id="q4_53"></div>
        <div class="seccion q5" id="q5_53"></div>
        <div class="titulo_diente">53</div>
      </div>
      <div class="diente" id="diente_52">
        <div class="seccion q1" id="q1_52"></div>
        <div class="seccion q2" id="q2_52"></div>
        <div class="seccion q3" id="q3_52"></div>
        <div class="seccion q4" id="q4_52"></div>
        <div class="seccion q5" id="q5_52"></div>
        <div class="titulo_diente">52</div>
      </div>
      <div class="diente" id="diente_51">
        <div class="seccion q1" id="q1_51"></div>
        <div class="seccion q2" id="q2_51"></div>
        <div class="seccion q3" id="q3_51"></div>
        <div class="seccion q4" id="q4_51"></div>
        <div class="seccion q5" id="q5_51"></div>
        <div class="titulo_diente">51</div>
      </div>
    </div>
    <div class="cuadrante" id="cuadranteInt_4">
      <div class="diente" id="diente_85">
        <div class="seccion q1" id="q1_85"></div>
        <div class="seccion q2" id="q2_85"></div>
        <div class="seccion q3" id="q3_85"></div>
        <div class="seccion q4" id="q4_85"></div>
        <div class="seccion q5" id="q5_85"></div>
        <div class="titulo_diente">85</div>
      </div>
      <div class="diente" id="diente_84">
        <div class="seccion q1" id="q1_84"></div>
        <div class="seccion q2" id="q2_84"></div>
        <div class="seccion q3" id="q3_84"></div>
        <div class="seccion q4" id="q4_84"></div>
        <div class="seccion q5" id="q5_84"></div>
        <div class="titulo_diente">84</div>
      </div>
      <div class="diente" id="diente_83">
        <div class="seccion q1" id="q1_83"></div>
        <div class="seccion q2" id="q2_83"></div>
        <div class="seccion q3" id="q3_83"></div>
        <div class="seccion q4" id="q4_83"></div>
        <div class="seccion q5" id="q5_83"></div>
        <div class="titulo_diente">83</div>
      </div>
      <div class="diente" id="diente_82">
        <div class="seccion q1" id="q1_82"></div>
        <div class="seccion q2" id="q2_82"></div>
        <div class="seccion q3" id="q3_82"></div>
        <div class="seccion q4" id="q4_82"></div>
        <div class="seccion q5" id="q5_82"></div>
        <div class="titulo_diente">82</div>
      </div>
      <div class="diente" id="diente_81">
        <div class="seccion q1" id="q1_81"></div>
        <div class="seccion q2" id="q2_81"></div>
        <div class="seccion q3" id="q3_81"></div>
        <div class="seccion q4" id="q4_81"></div>
        <div class="seccion q5" id="q5_81"></div>
        <div class="titulo_diente">81</div>
      </div>
    </div>
  </div>

  <div class="cuadrante_inf_int">
    <div class="cuadrante" id="cuadranteInt_2">
      <div class="diente" id="diente_61">
        <div class="seccion q1" id="q1_61"></div>
        <div class="seccion q2" id="q2_61"></div>
        <div class="seccion q3" id="q3_61"></div>
        <div class="seccion q4" id="q4_61"></div>
        <div class="seccion q5" id="q5_61"></div>
        <div class="titulo_diente">61</div>
      </div>
      <div class="diente" id="diente_62">
        <div class="seccion q1" id="q1_62"></div>
        <div class="seccion q2" id="q2_62"></div>
        <div class="seccion q3" id="q3_62"></div>
        <div class="seccion q4" id="q4_62"></div>
        <div class="seccion q5" id="q5_62"></div>
        <div class="titulo_diente">62</div>
      </div>
      <div class="diente" id="diente_63">
        <div class="seccion q1" id="q1_63"></div>
        <div class="seccion q2" id="q2_63"></div>
        <div class="seccion q3" id="q3_63"></div>
        <div class="seccion q4" id="q4_63"></div>
        <div class="seccion q5" id="q5_63"></div>
        <div class="titulo_diente">63</div>
      </div>
      <div class="diente" id="diente_64">
        <div class="seccion q1" id="q1_64"></div>
        <div class="seccion q2" id="q2_64"></div>
        <div class="seccion q3" id="q3_64"></div>
        <div class="seccion q4" id="q4_64"></div>
        <div class="seccion q5" id="q5_64"></div>
        <div class="titulo_diente">64</div>
      </div>
      <div class="diente" id="diente_65">
        <div class="seccion q1" id="q1_65"></div>
        <div class="seccion q2" id="q2_65"></div>
        <div class="seccion q3" id="q3_65"></div>
        <div class="seccion q4" id="q4_65"></div>
        <div class="seccion q5" id="q5_65"></div>
        <div class="titulo_diente">65</div>
      </div>
    </div>
    <div class="cuadrante" id="cuadranteInt_3">
      <div class="diente" id="diente_71">
        <div class="seccion q1" id="q1_71"></div>
        <div class="seccion q2" id="q2_71"></div>
        <div class="seccion q3" id="q3_71"></div>
        <div class="seccion q4" id="q4_71"></div>
        <div class="seccion q5" id="q5_71"></div>
        <div class="titulo_diente">71</div>
      </div>
      <div class="diente" id="diente_72">
        <div class="seccion q1" id="q1_72"></div>
        <div class="seccion q2" id="q2_72"></div>
        <div class="seccion q3" id="q3_72"></div>
        <div class="seccion q4" id="q4_72"></div>
        <div class="seccion q5" id="q5_72"></div>
        <div class="titulo_diente">72</div>
      </div>
      <div class="diente" id="diente_73">
        <div class="seccion q1" id="q1_73"></div>
        <div class="seccion q2" id="q2_73"></div>
        <div class="seccion q3" id="q3_73"></div>
        <div class="seccion q4" id="q4_73"></div>
        <div class="seccion q5" id="q5_73"></div>
        <div class="titulo_diente">73</div>
      </div>
      <div class="diente" id="diente_74">
        <div class="seccion q1" id="q1_74"></div>
        <div class="seccion q2" id="q2_74"></div>
        <div class="seccion q3" id="q3_74"></div>
        <div class="seccion q4" id="q4_74"></div>
        <div class="seccion q5" id="q5_74"></div>
        <div class="titulo_diente">74</div>
      </div>
      <div class="diente" id="diente_75">
        <div class="seccion q1" id="q1_75"></div>
        <div class="seccion q2" id="q2_75"></div>
        <div class="seccion q3" id="q3_75"></div>
        <div class="seccion q4" id="q4_75"></div>
        <div class="seccion q5" id="q5_75"></div>
        <div class="titulo_diente">75</div>
      </div>
    </div>
  </div>

 </div>

  <div class="cuadrante_inf">
    <div class="cuadrante" id="cuadrante_4">
      <div class="diente" id="diente_48">
        <div class="seccion q1" id="q1_48"></div>
        <div class="seccion q2" id="q2_48"></div>
        <div class="seccion q3" id="q3_48"></div>
        <div class="seccion q4" id="q4_48"></div>
        <div class="seccion q5" id="q5_48"></div>
        <div class="titulo_diente">48</div>
      </div>
      <div class="diente" id="diente_47">
        <div class="seccion q1" id="q1_47"></div>
        <div class="seccion q2" id="q2_47"></div>
        <div class="seccion q3" id="q3_47"></div>
        <div class="seccion q4" id="q4_47"></div>
        <div class="seccion q5" id="q5_47"></div>
        <div class="titulo_diente">47</div>
      </div>
      <div class="diente" id="diente_46">
        <div class="seccion q1" id="q1_46"></div>
        <div class="seccion q2" id="q2_46"></div>
        <div class="seccion q3" id="q3_46"></div>
        <div class="seccion q4" id="q4_46"></div>
        <div class="seccion q5" id="q5_46"></div>
        <div class="titulo_diente">46</div>
      </div>
      <div class="diente" id="diente_45">
        <div class="seccion q1" id="q1_45"></div>
        <div class="seccion q2" id="q2_45"></div>
        <div class="seccion q3" id="q3_45"></div>
        <div class="seccion q4" id="q4_45"></div>
        <div class="seccion q5" id="q5_45"></div>
        <div class="titulo_diente">45</div>
      </div>
      <div class="diente" id="diente_44">
        <div class="seccion q1" id="q1_44"></div>
        <div class="seccion q2" id="q2_44"></div>
        <div class="seccion q3" id="q3_44"></div>
        <div class="seccion q4" id="q4_44"></div>
        <div class="seccion q5" id="q5_44"></div>
        <div class="titulo_diente">44</div>
      </div>
      <div class="diente" id="diente_43">
        <div class="seccion q1" id="q1_43"></div>
        <div class="seccion q2" id="q2_43"></div>
        <div class="seccion q3" id="q3_43"></div>
        <div class="seccion q4" id="q4_43"></div>
        <div class="seccion q5" id="q5_43"></div>
        <div class="titulo_diente">43</div>
      </div>
      <div class="diente" id="diente_42">
        <div class="seccion q1" id="q1_42"></div>
        <div class="seccion q2" id="q2_42"></div>
        <div class="seccion q3" id="q3_42"></div>
        <div class="seccion q4" id="q4_42"></div>
        <div class="seccion q5" id="q5_42"></div>
        <div class="titulo_diente">42</div>
      </div>
      <div class="diente" id="diente_41">
        <div class="seccion q1" id="q1_41"></div>
        <div class="seccion q2" id="q2_41"></div>
        <div class="seccion q3" id="q3_41"></div>
        <div class="seccion q4" id="q4_41"></div>
        <div class="seccion q5" id="q5_41"></div>
        <div class="titulo_diente">41</div>
      </div>
    </div>
    <div class="cuadrante" id="cuadrante_3">
      <div class="diente"  id="diente_31">
        <div class="seccion q1" id="q1_31"></div>
        <div class="seccion q2" id="q2_31"></div>
        <div class="seccion q3" id="q3_31"></div>
        <div class="seccion q4" id="q4_31"></div>
        <div class="seccion q5" id="q5_31"></div>
        <div class="titulo_diente">31</div>
      </div>
      <div class="diente"  id="diente_32">
        <div class="seccion q1" id="q1_32"></div>
        <div class="seccion q2" id="q2_32"></div>
        <div class="seccion q3" id="q3_32"></div>
        <div class="seccion q4" id="q4_32"></div>
        <div class="seccion q5" id="q5_32"></div>
        <div class="titulo_diente">32</div>
      </div>
      <div class="diente"  id="diente_33">
        <div class="seccion q1" id="q1_33"></div>
        <div class="seccion q2" id="q2_33"></div>
        <div class="seccion q3" id="q3_33"></div>
        <div class="seccion q4" id="q4_33"></div>
        <div class="seccion q5" id="q5_33"></div>
        <div class="titulo_diente">33</div>
      </div>
      <div class="diente"  id="diente_34">
        <div class="seccion q1" id="q1_34"></div>
        <div class="seccion q2" id="q2_34"></div>
        <div class="seccion q3" id="q3_34"></div>
        <div class="seccion q4" id="q4_34"></div>
        <div class="seccion q5" id="q5_34"></div>
        <div class="titulo_diente">34</div>
      </div>
      <div class="diente"  id="diente_35">
        <div class="seccion q1" id="q1_35"></div>
        <div class="seccion q2" id="q2_35"></div>
        <div class="seccion q3" id="q3_35"></div>
        <div class="seccion q4" id="q4_35"></div>
        <div class="seccion q5" id="q5_35"></div>
        <div class="titulo_diente">35</div>
      </div>
      <div class="diente"  id="diente_36">
        <div class="seccion q1" id="q1_36"></div>
        <div class="seccion q2" id="q2_36"></div>
        <div class="seccion q3" id="q3_36"></div>
        <div class="seccion q4" id="q4_36"></div>
        <div class="seccion q5" id="q5_36"></div>
        <div class="titulo_diente">36</div>
      </div>
      <div class="diente"  id="diente_37">
        <div class="seccion q1" id="q1_37"></div>
        <div class="seccion q2" id="q2_37"></div>
        <div class="seccion q3" id="q3_37"></div>
        <div class="seccion q4" id="q4_37"></div>
        <div class="seccion q5" id="q5_37"></div>
        <div class="titulo_diente">37</div>
      </div>
      <div class="diente"  id="diente_38">
        <div class="seccion q1" id="q1_38"></div>
        <div class="seccion q2" id="q2_38"></div>
        <div class="seccion q3" id="q3_38"></div>
        <div class="seccion q4" id="q4_38"></div>
        <div class="seccion q5" id="q5_38"></div>
        <div class="titulo_diente">38</div>
      </div>
    </div>
  </div>
</div>

    `;
    return odontogramaHTML;
}
