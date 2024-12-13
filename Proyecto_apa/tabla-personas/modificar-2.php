<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Modificar 2");

$id = recoge("id");


$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

$registroEncontradoOk = false;

if ($idOk) {
    $consulta = "SELECT COUNT(*) FROM Alumno
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Registro no encontrado.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

if ($idOk && $registroEncontradoOk) {
    $consulta = "SELECT * FROM Alumno
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();
        print "<form action=\"modificar-3.php\" method=\"get\">";
        print "      <p>Modifique los campos que desee:</p>";
        
        print "      <table>";
        print "        <tr>";
        print "          <td>Nombre:</td>";
        print "          <td><input type=\"text\" name=\"nombre\" value=\"$registro[Nombre]\" autofocus></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Apellidos:</td>";
        print "          <td><input type=\"text\" name=\"apellidos\" value=\"$registro[Apellido]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Fecha de Nacimiento:</td>";
        print "          <td><input type=\"date\" name=\"fecha_nacimiento\" value=\"$registro[Fecha_Nacimiento]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Número Socio IHA:</td>";
        print "          <td><input type=\"text\" name=\"numero_socio_iha\" value=\"$registro[Numero_Socio_IHA]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Cinturón:</td>";
        print "          <td><input type=\"text\" name=\"cinturon\" value=\"$registro[Cinturon]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Nivel:</td>";
        print "          <td><input type=\"text\" name=\"nivel\" value=\"$registro[Nivel]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>País:</td>";
        print "          <td><input type=\"text\" name=\"pais\" value=\"$registro[Pais]\"></td>";
        print "        </tr>";
        print "        <tr>";
        print "          <td>Correo:</td>";
        print "          <td><input type=\"email\" name=\"correo\" value=\"$registro[Correo]\"></td>";
        print "        </tr>";

        print "      </table>";
        
        print "      <p>";
        print "        <input type=\"hidden\" name=\"id\" value=\"$id\">";
        print "        <input type=\"submit\" value=\"Actualizar\">";
        print "        <input type=\"reset\" value=\"Reiniciar formulario\">";
        print "      </p>";
        print "    </form>";
    }
}

pie();
?>
