<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Buscar 1");

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
    // Mostramos el formulario de búsqueda
?>

<form action="buscar-2.php" method="post">
    <h2>Introduzca el nombre del registro a buscar</h2>
    <table>
        <tr>
            <td>Nombre:</td>
            <td><input type="text" name="nombre" autofocus></td>
        </tr>
    </table>
    <p>
        <input type="submit" value="Buscar">
        <input type="reset" value="Reiniciar formulario">
    </p>
</form>

<?php
}

pie();
?>
