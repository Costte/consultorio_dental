

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="stylus.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
</head>
<body>
  <title>Historial Paciente</title>
</head>

<body>
  <div class="left_histo">
  <a href="pacientes.php"><i class='bx bx-left-arrow-alt'></i></a>
  </div>
  
  <div class="histo_medi">

    <div>
      <div class="bienvenido">
        <div>
          <h2 style="color: DarkMagenta;">Historial Medico </h2>
          <div id="fecha"></div>
        </div>
        <div>
          <img src="images/small_smile.png" alt="" width="200px" height="100px">
        </div>
      </div>
    </div>

    <?php
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "consultorio_dental";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
      die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener el ID del paciente desde la solicitud GET
    $idPaciente = $_GET['id'];

    // Consulta SQL para obtener la información del paciente y del doctor asignado
    $sqlPaciente = "SELECT pacientes.nombre, pacientes.edad, pacientes.apellido_paterno, pacientes.apellido_materno, pacientes.fecha_nacimiento, pacientes.fecha_ingreso, pacientes.sexo, doctores.nombre AS nombre_doctor, doctores.apellido_paterno AS apellido_paterno_doctor, doctores.apellido_materno AS apellido_materno_doctor
        FROM pacientes
        INNER JOIN doctores ON pacientes.id_doctor = doctores.id
        WHERE pacientes.id = $idPaciente";

    $resultadoPaciente = $conn->query($sqlPaciente);

    if ($resultadoPaciente->num_rows > 0) {
      // Mostrar los datos del paciente en una tabla HTML
      echo "<h2 class='titulo'>Información del Paciente</h2>";
      echo "<table class='histo_medico_tabla'>
            <tr>
                <th>Nombre Completo</th>
                <th>Edad</th>
                <th>Fecha de Nacimiento</th>
                <th>Fecha de Ingreso</th>
                <th>Sexo</th>
                <th>Doctor Asignado</th>
            </tr>";
      while ($filaPaciente = $resultadoPaciente->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $filaPaciente['nombre'] . " " . $filaPaciente['apellido_paterno'] . " " . $filaPaciente['apellido_materno'] . "</td>";
        echo "<td>" . $filaPaciente['edad'] . "</td>";
        echo "<td>" . $filaPaciente['fecha_nacimiento'] . "</td>";
        echo "<td>" . $filaPaciente['fecha_ingreso'] . "</td>";
        echo "<td>" . $filaPaciente['sexo'] . "</td>";
        echo "<td>" . $filaPaciente['nombre_doctor'] . " " . $filaPaciente['apellido_paterno_doctor'] . " " . $filaPaciente['apellido_materno_doctor'] . "</td>";
        echo "</tr>";
      }
      echo "</table>";

      // Consulta SQL para obtener las citas del paciente
      $sqlCitas = "SELECT citas.id, citas.fecha, citas.descripcion_cita, citas.id_tratamiento, citas.comentarios, tratamientos.nombre AS nombre_tratamiento
                FROM citas
                INNER JOIN tratamientos ON citas.id_tratamiento = tratamientos.id
                WHERE citas.id_paciente = $idPaciente";

      $resultadoCitas = $conn->query($sqlCitas);

      if ($resultadoCitas->num_rows > 0) {
        // Mostrar los datos de las citas en una segunda tabla HTML
        echo "<h2 class='titulo'>Citas del Paciente</h2>";
        echo "<table class='histo_medico_tabla'>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción de la Cita</th>
                    <th>Tratamiento</th>
                    <th>Comentarios</th>
                </tr>";
        while ($filaCitas = $resultadoCitas->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . $filaCitas['fecha'] . "</td>";
          echo "<td>" . $filaCitas['descripcion_cita'] . "</td>";
          echo "<td>" . $filaCitas['nombre_tratamiento'] . "</td>";
          echo "<td><textarea class='comentario-textarea' id='comentario_" . $filaCitas['id'] . "' onchange='guardarComentario(" . $filaCitas['id'] . ", this.value)'>" . $filaCitas['comentarios'] . "</textarea></td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p>No hay citas para este paciente.</p>";
      }
    } else {
      echo "<p>No se encontró información para este paciente.</p>";
    }

    echo "<h2 class='titulo'>Tu odontograma</h2>";

    echo " <div class='odontograma'>
<div class='cuadrante_sup'>
  <div class='cuadrante' id='cuadrante_1'>
  <div class='diente' id='diente_18'>
  <div class='seccion q1' id='q1_18' data-seccion='Bucal'></div>
  <div class='seccion q2' id='q2_18' data-seccion='Distal'></div>
  <div class='seccion q3' id='q3_18' data-seccion='Lingual'></div>
  <div class='seccion q4' id='q4_18' data-seccion='Mesial'></div>
  <div class='seccion q5' id='q5_18' data-seccion='Oclusal'></div>  
  <div class='titulo_diente'>18</div>
</div>
    <div class='diente' id='diente_17'>
      <div class='seccion q1' id='q1_17' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_17' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_17' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_17' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_17' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>17</div>
    </div>
    <div class='diente' id='diente_16'>
      <div class='seccion q1' id='q1_16' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_16' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_16' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_16' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_16' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>16</div>
    </div>
    <div class='diente' id='diente_15'>
      <div class='seccion q1' id='q1_15' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_15' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_15' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_15' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_15' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>15</div>
    </div>
    <div class='diente' id='diente_14'>
      <div class='seccion q1' id='q1_14' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_14' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_14' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_14' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_14' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>14</div>
    </div>
    <div class='diente' id='diente_13'>
      <div class='seccion q1' id='q1_13' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_13' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_13' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_13' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_13' data-seccion='Incisal'></div>
      <div class='titulo_diente'>13</div>
    </div>
    <div class='diente' id='diente_12'>
      <div class='seccion q1' id='q1_12' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_12' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_12' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_12' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_12' data-seccion='Incisal'></div>
      <div class='titulo_diente'>12</div>
    </div>
    <div class='diente' id='diente_11'>
      <div class='seccion q1' id='q1_11' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_11' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_11' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_11' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_11' data-seccion='Incisal'></div>
      <div class='titulo_diente'>11</div>
    </div>
  </div>
  <div class='cuadrante' id='cuadrante_2'>
    <div class='diente' id='diente_21'>
      <div class='seccion q1' id='q1_21' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_21' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_21' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_21' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_21' data-seccion='Incisal'></div>
      <div class='titulo_diente'>21</div>
    </div>
    <div class='diente' id='diente_22'>
      <div class='seccion q1' id='q1_22' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_22' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_22' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_22' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_22' data-seccion='Incisal'></div>
      <div class='titulo_diente'>22</div>
    </div>
    <div class='diente' id='diente_23'>
      <div class='seccion q1' id='q1_23' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_23' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_23' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_23' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_23' data-seccion='Incisal'></div>
      <div class='titulo_diente'>23</div>
    </div>
    <div class='diente' id='diente_24'>
      <div class='seccion q1' id='q1_24' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_24' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_24' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_24' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_24' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>24</div>
    </div>
    <div class='diente' id='diente_25'>
      <div class='seccion q1' id='q1_25' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_25' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_25' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_25' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_25' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>25</div>
    </div>
    <div class='diente' id='diente_26'>
      <div class='seccion q1' id='q1_26' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_26' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_26' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_26' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_26' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>26</div>
    </div>
    <div class='diente' id='diente_27'>
      <div class='seccion q1' id='q1_27' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_27' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_27' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_27' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_27' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>27</div>
    </div>
    <div class='diente' id='diente_28'>
      <div class='seccion q1' id='q1_28' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_28' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_28' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_28' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_28' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>28</div>
    </div>
  </div>
</div>

<div class='cuandrantes_int'>
<div class='cuadrante_sup_int'>
  <div class='cuadrante' id='cuadranteInt_1'>
    <div class='diente' id='diente_55'>
      <div class='seccion q1' id='q1_55' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_55' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_55' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_55' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_55' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>55</div>
    </div>
    <div class='diente' id='diente_54'>
      <div class='seccion q1' id='q1_54' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_54' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_54' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_54' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_54' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>54</div>
    </div>
    <div class='diente' id='diente_53'>
      <div class='seccion q1' id='q1_53' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_53' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_53' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_53' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_53' data-seccion='Incisal'></div>
      <div class='titulo_diente'>53</div>
    </div>
    <div class='diente' id='diente_52'>
      <div class='seccion q1' id='q1_52' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_52' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_52' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_52' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_52' data-seccion='Incisal'></div>
      <div class='titulo_diente'>52</div>
    </div>
    <div class='diente' id='diente_51'>
      <div class='seccion q1' id='q1_51' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_51' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_51' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_51' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_51' data-seccion='Incisal'></div>
      <div class='titulo_diente'>51</div>
    </div>
  </div>
  <div class='cuadrante' id='cuadranteInt_4'>
    <div class='diente' id='diente_85'>
      <div class='seccion q1' id='q1_85' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_85' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_85' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_85' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_85' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>85</div>
    </div>
    <div class='diente' id='diente_84'>
      <div class='seccion q1' id='q1_84' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_84' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_84' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_84' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_84' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>84</div>
    </div>
    <div class='diente' id='diente_83'>
      <div class='seccion q1' id='q1_83' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_83' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_83' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_83' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_83' data-seccion='Incisal'></div>
      <div class='titulo_diente'>83</div>
    </div>
    <div class='diente' id='diente_82'>
      <div class='seccion q1' id='q1_82' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_82' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_82' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_82' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_82' data-seccion='Incisal'></div>
      <div class='titulo_diente'>82</div>
    </div>
    <div class='diente' id='diente_81'>
      <div class='seccion q1' id='q1_81' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_81' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_81' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_81' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_81' data-seccion='Incisal'></div>
      <div class='titulo_diente'>81</div>
    </div>
  </div>
</div>

<div class='cuadrante_inf_int'>
  <div class='cuadrante' id='cuadranteInt_2'>
    <div class='diente' id='diente_61'>
      <div class='seccion q1' id='q1_61' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_61' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_61' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_61' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_61' data-seccion='Incisal'></div>
      <div class='titulo_diente'>61</div>
    </div>
    <div class='diente' id='diente_62'>
      <div class='seccion q1' id='q1_62' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_62' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_62' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_62' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_62' data-seccion='Incisal'></div>
      <div class='titulo_diente'>62</div>
    </div>
    <div class='diente' id='diente_63'>
      <div class='seccion q1' id='q1_63' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_63' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_63' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_63' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_63' data-seccion='Incisal'></div>
      <div class='titulo_diente'>63</div>
    </div>
    <div class='diente' id='diente_64'>
      <div class='seccion q1' id='q1_64' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_64' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_64' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_64' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_64' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>64</div>
    </div>
    <div class='diente' id='diente_65'>
      <div class='seccion q1' id='q1_65' data-seccion='Bucal'></div>
      <div class='seccion q2' id='q2_65' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_65' data-seccion='Lingual'></div>
      <div class='seccion q4' id='q4_65' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_65' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>65</div>
    </div>
  </div>
  <div class='cuadrante' id='cuadranteInt_3'>
    <div class='diente' id='diente_71'>
      <div class='seccion q1' id='q1_71' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_71' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_71' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_71' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_71' data-seccion='Incisal'></div>
      <div class='titulo_diente'>71</div>
    </div>
    <div class='diente' id='diente_72'>
      <div class='seccion q1' id='q1_72' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_72' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_72' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_72' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_72' data-seccion='Incisal'></div>
      <div class='titulo_diente'>72</div>
    </div>
    <div class='diente' id='diente_73'>
      <div class='seccion q1' id='q1_73' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_73' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_73' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_73' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_73' data-seccion='Incisal'></div>
      <div class='titulo_diente'>73</div>
    </div>
    <div class='diente' id='diente_74'>
      <div class='seccion q1' id='q1_74' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_74' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_74' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_74' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_74' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>74</div>
    </div>
    <div class='diente' id='diente_75'>
      <div class='seccion q1' id='q1_75' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_75' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_75' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_75' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_75' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>75</div>
    </div>
  </div>
</div>

</div>

<div class='cuadrante_inf'>
  <div class='cuadrante' id='cuadrante_4'>
    <div class='diente' id='diente_48'>
      <div class='seccion q1' id='q1_48' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_48' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_48' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_48' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_48' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>48</div>
    </div>
    <div class='diente' id='diente_47'>
      <div class='seccion q1' id='q1_47' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_47' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_47' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_47' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_47' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>47</div>
    </div>
    <div class='diente' id='diente_46'>
      <div class='seccion q1' id='q1_46' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_46' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_46' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_46' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_46' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>46</div>
    </div>
    <div class='diente' id='diente_45'>
      <div class='seccion q1' id='q1_45' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_45' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_45' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_45' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_45' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>45</div>
    </div>
    <div class='diente' id='diente_44'>
      <div class='seccion q1' id='q1_44' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_44' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_44' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_44' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_44' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>44</div>
    </div>
    <div class='diente' id='diente_43'>
      <div class='seccion q1' id='q1_43' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_43' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_43' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_43' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_43' data-seccion='Incisal'></div>
      <div class='titulo_diente'>43</div>
    </div>
    <div class='diente' id='diente_42'>
      <div class='seccion q1' id='q1_42' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_42' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_42' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_42' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_42' data-seccion='Incisal'></div>
      <div class='titulo_diente'>42</div>
    </div>
    <div class='diente' id='diente_41'>
      <div class='seccion q1' id='q1_41' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_41' data-seccion='Distal'></div>
      <div class='seccion q3' id='q3_41' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_41' data-seccion='Mesial'></div>
      <div class='seccion q5' id='q5_41' data-seccion='Incisal'></div>
      <div class='titulo_diente'>41</div>
    </div>
  </div>
  <div class='cuadrante' id='cuadrante_3'>
    <div class='diente'  id='diente_31'>
      <div class='seccion q1' id='q1_31' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_31' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_31' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_31' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_31' data-seccion='Incisal'></div>
      <div class='titulo_diente'>31</div>
    </div>
    <div class='diente'  id='diente_32'>
      <div class='seccion q1' id='q1_32' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_32' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_32' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_32' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_32' data-seccion='Incisal'></div>
      <div class='titulo_diente'>32</div>
    </div>
    <div class='diente'  id='diente_33'>
      <div class='seccion q1' id='q1_33' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_33' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_33' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_33' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_33' data-seccion='Incisal'></div>
      <div class='titulo_diente'>33</div>
    </div>
    <div class='diente'  id='diente_34'>
      <div class='seccion q1' id='q1_34' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_34' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_34' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_34' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_34' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>34</div>
    </div>
    <div class='diente'  id='diente_35'>
      <div class='seccion q1' id='q1_35' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_35' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_35' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_35' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_35' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>35</div>
    </div>
    <div class='diente'  id='diente_36'>
      <div class='seccion q1' id='q1_36' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_36' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_36' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_36' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_36' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>36</div>
    </div>
    <div class='diente'  id='diente_37'>
      <div class='seccion q1' id='q1_37' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_37' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_37' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_37' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_37' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>37</div>
    </div>
    <div class='diente'  id='diente_38'>
      <div class='seccion q1' id='q1_38' data-seccion='Palatino'></div>
      <div class='seccion q2' id='q2_38' data-seccion='Mesial'></div>
      <div class='seccion q3' id='q3_38' data-seccion='Bucal'></div>
      <div class='seccion q4' id='q4_38' data-seccion='Distal'></div>
      <div class='seccion q5' id='q5_38' data-seccion='Oclusal'></div>
      <div class='titulo_diente'>38</div>
    </div>
  </div>

</div>

<div>
<h2 class='refere_titu'>Referencias</h2>
<div class='refere_diente'>

<div class='cont_seccion'>
<div class='diente_re diente_1'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' ></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
  
  </div>
  
  <div class='titulo_diente_refe'>Caries</div>
  </div>


  <div class='cont_seccion'>
  <div class='diente_re diente_2'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' ></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
    
  </div>
  <div class='titulo_diente_refe'>Restauracion adaptada</div>
  </div>
  

  <div class='cont_seccion'>
  <div class='diente_re diente_3'  id='diente'>
  <div class='seccion q1' id='q1' ></div>
  <div class='seccion q2' id='q2' ></div>
  <div class='seccion q3' id='q3' ></div>
  <div class='seccion q4' id='q4' ></div>
  <div class='seccion q5' id='q5' ></div>
  
</div>
<div class='titulo_diente_refe'>Restauracion Desadaptada</div>
</div>
  

  <div class='cont_seccion'>
  <div class='diente_re diente_4'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' ></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
    
  </div>
  <div class='titulo_diente_refe'>Extraccion Indicado</div>
  </div>
  

  <div class='cont_seccion'>
  <div class='diente_re diente_5'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' ></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
   
  </div>
  <div class='titulo_diente_refe'>Diente Ausente</div>
  </div>
  

  <div class='cont_seccion'>
  <div class='diente_re diente_6'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' '></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
    
  </div>
  <div class='titulo_diente_refe'>Carana Adaptada</div>
  </div>
  

  <div class='cont_seccion'>
  <div class='diente_re diente_7'  id='diente'>
    <div class='seccion q1' id='q1' ></div>
    <div class='seccion q2' id='q2' ></div>
    <div class='seccion q3' id='q3' ></div>
    <div class='seccion q4' id='q4' ></div>
    <div class='seccion q5' id='q5' ></div>
    
  </div>
  <div class='titulo_diente_refe'>Corona Desadaptada</div>
  </div>
  
</div>
</div>


</div> ";

    echo "
<!-- Modal -->
<div id='myModal' class='modal'>
  <div class='modal-content'>
    <span class='close'>&times;</span>
    <h2>Seleccionar Daño</h2>
    <!-- Agregar un apartado para mostrar el ID del paciente -->
    <div id='idPacienteInfo'>
      <label for='id_paciente'>ID del Paciente:</label>
      <span id='id_paciente'></span>
    </div>

    <div id='dienteInfo'>
      <label for='numeroDiente'>Número de Diente:</label>
      <input type='text' id='numeroDiente' disabled>

      <label for='seccion_dano'>Seccion:</label>
      <input type='text' id='seccion_dano' disabled>
      <div id='idPacienteInfo'>
      <label for='numero_sec'>Seccion:</label>
      <span id='numero_sec'></span>
      </div>
      <label for='tipoDano'>Tipo de Daño:</label>
      <select id='tipoDano'>
        <option value='caries'>Caries</option>
        <option value='restauracion adaptada'>Restauracion Adaptada</option>
        <option value='restauracion desadaptada'>Restauracion Desadaptada</option>
        <option value='extraccion indicado'>Extraccion Indicado</option>
        <option value='diente ausente'>Diente Ausente</option>
        <option value='corona adaptada'>Corona Adaptada</option>
        <option value='corona desadaptada'>Corona Desadaptada</option>
        <!-- Agrega más opciones según sea necesario -->
      </select>
      <button id='guardar' onclick='guardar()'>Guardar</button>
    </div>
    <div id='resultados_danos' class='result_general'></div>
  </div>
</div>


 ";
    echo "<script>";
    echo "document.addEventListener('DOMContentLoaded', function() {";
    echo "  document.getElementById('id_paciente').textContent = $idPaciente;";
    echo "});";
    echo "</script>";

    echo "<button onclick='guardarComoPDF()'>Guardar como PDF</button>";

    // Cerrar conexión
    $conn->close();
    ?>
  </div>
  <script>
    function guardarComentario(idCita, comentario) {
      var xhrGuardarComentario = new XMLHttpRequest();
      xhrGuardarComentario.onreadystatechange = function() {
        if (xhrGuardarComentario.readyState == 4 && xhrGuardarComentario.status == 200) {
          console.log("Comentario guardado correctamente.");
        }
      };
      xhrGuardarComentario.open("POST", "guardar_comentario.php", true);
      xhrGuardarComentario.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhrGuardarComentario.send("id_cita=" + idCita + "&comentario=" + encodeURIComponent(comentario));
    }
  </script>

  <script src="odontograma.js"></script>
  <script src="guardar_danos.js"></script>
  <script src="busqueda_danos.js"></script>

  <!-- Agrega esto en tu HTML -->
  <script>
    // Función para obtener los tipos de daño asignados a cada div
    $(document).ready(function() {
      // Llamar a la función obtenerDanosAsignados() cuando la página se carga

      // Obtener el valor de idPaciente del parámetro de consulta del URL
      var urlParams = new URLSearchParams(window.location.search);
      var idPaciente = urlParams.get('id');

      // Llamar a la función obtenerDanosAsignados() con el valor de idPaciente obtenido
      obtenerDanosAsignados(idPaciente);
      // Definir la función obtenerDanosAsignados()
      function obtenerDanosAsignados(idPaciente) {
        // Realizar una solicitud AJAX para obtener los daños asignados al paciente
        $.ajax({
          url: 'obtener_dano.php', // Nombre del script PHP que consulta la base de datos
          type: 'POST',
          data: {
            id_paciente: idPaciente
          },
          dataType: 'json',
          success: function(data) {
            console.log("Datos recibidos:", data); // Imprimir los datos recibidos

            // Iterar sobre los resultados obtenidos
            data.forEach(function(dano) {
              console.log("Aplicando estilos para:", dano); // Imprimir datos del daño actual

              // Construir el selector para el div de sección del diente
              var selector = '#diente_' + dano.numero_diente + ' #q' + dano.numero_sec + '_' + dano.numero_diente;


              console.log("Selector:", selector); // Imprimir el selector

              // Aplicar estilos según el tipo de daño
              switch (dano.tipo_dano) {
                case 'caries':
                  console.log("Aplicando estilo para caries"); // Mensaje de depuración
                  $(selector).css('border', '4px solid red');
                  break;
                case 'restauracion adaptada':
                  $(selector).css('border', '4px solid blue');
                  break;
                case 'restauracion desadaptada':
                  $(selector).css('border', '4px solid red');
                  $(selector).css('background-color', 'blue');
                  break;
                case 'extraccion indicado':
                  $('#diente_' + dano.numero_diente).find('.seccion').css('background', 'green');
                  $('#diente_' + dano.numero_diente).find('.seccion').css('border', '2px solid lime');
                  break;
                case 'diente ausente':
                  $('#diente_' + dano.numero_diente).find('.seccion').css('background', 'red');
                  $('#diente_' + dano.numero_diente).find('.seccion').css('border', '2px solid Maroon');
                  break;
                case 'corona adaptada':
                  $('#diente_' + dano.numero_diente).find('.seccion').css('border', '4px solid red');
                  break;
                case 'corona desadaptada':
                  $('#diente_' + dano.numero_diente).find('.seccion').css('border', '4px solid blue');
                  break;
                  // Agrega más casos según sea necesario
                default:
                  console.log("Tipo de daño desconocido:", dano.tipo_dano); // Mensaje de depuración
                  break;
              }
            });
          },
          error: function(xhr, status, error) {
            console.error("Error al obtener los daños: " + error);
          }
        });
      }
    });

    setInterval(obtenerDanosAsignados, 5000); // Cada 5 segundos
    // Establecer un intervalo para refrescar la página cada 5 segundos (5000 milisegundos)
setInterval(function() {
    location.reload();
}, 5000);
    
  </script>
 <script>
function guardarComoPDF() {
  const element = document.body;

  html2pdf()
    .from(element)
    .save('pagina.pdf');
}
</script>
</body>

</html>