<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Listar");

// Comprobamos si la base de datos contiene registros
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM Alumno";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No se ha creado todavía ningún registro.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// Si todas las comprobaciones han tenido éxito ...
if ($hayRegistrosOk) {
    // Recuperamos todos los registros para mostrarlos en una <table>
    $consulta = "SELECT Nombre, Apellido, Fecha_Nacimiento, Numero_Socio_IHA, Cinturon, Nivel, Pais, Correo FROM Alumno";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
    
?>
    <p>Listado completo de registros:</p>

      <table class="conborde franjas">
      <thead>
    <tr>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Fecha de Nacimiento</th>
        <th>Número Socio IHA</th>
        <th>Cinturón</th>
        <th>Nivel</th>
        <th>País</th>
        <th>Correo</th>
    </tr>
  </thead>
<?php
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            print "          <td>{$registro['Nombre']}</td>\n";
            print "          <td>{$registro['Apellido']}</td>\n";
            print "          <td>{$registro['Fecha_Nacimiento']}</td>\n";
            print "          <td>{$registro['Numero_Socio_IHA']}</td>\n";
            print "          <td>{$registro['Cinturon']}</td>\n";
            print "          <td>{$registro['Nivel']}</td>\n";
            print "          <td>{$registro['Pais']}</td>\n";
            print "          <td>{$registro['Correo']}</td>\n";
            print "        </tr>\n";
        }

        print "      </table>\n";
        print "    </form>\n";
    }
}

pie();
?>
