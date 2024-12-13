<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Borrar 1");

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
    $consulta = "SELECT id, Nombre, Apellido, Fecha_Nacimiento, Numero_Socio_IHA, Cinturon, Nivel, Pais, Correo FROM Alumno";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
?>
    <form action="borrar-2.php" method="post">
      <p>Marque el registro que quiera borrar:</p>
      <table class="conborde franjas">
        <thead>
          <tr>
          <th>Borrar</th>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Fecha de Nacimiento</th>
          <th>Número de Socio IHA</th>
          <th>Cinturón</th>
          <th>Nivel</th>
          <th>País</th>
          <th>Correo</th>
          </tr>
        </thead>
<?php
        foreach ($resultado as $registro) {
            print "        <tr>\n";
            print "          <td class=\"centrado\"><input type=\"radio\" name=\"id\" value=\"{$registro['id']}\" checked></td>\n";
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
?>
      </table>
      <p>
        <input type="submit" value="Borrar registro">
        <input type="reset" value="Reiniciar formulario">
      </p>
    </form>
<?php
    }
}

pie();
?>
