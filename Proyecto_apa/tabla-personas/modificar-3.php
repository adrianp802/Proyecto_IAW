<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Modificar 3");

$nombre               = recoge("nombre");
$apellidos            = recoge("apellidos");
$fecha_nacimiento     = recoge("fecha_nacimiento");
$numero_socio_iha     = recoge("numero_socio_iha");
$cinturon             = recoge("cinturon");
$nivel                = recoge("nivel");
$pais                 = recoge("pais");
$id                   = recoge("id");
$correo               = recoge("correo");

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que no se intenta crear un registro vacío
$registroNoVacioOk = false;

if ($nombre == "" && $apellidos == "" && $correo == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
} else {
    $registroNoVacioOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($idOk && $registroNoVacioOk) {
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

// Si todas las comprobaciones han tenido éxito ...
if ($idOk && $registroNoVacioOk && $registroEncontradoOk) {
    // Actualizamos el registro con los datos recibidos
    $consulta = "UPDATE Alumno
    SET Nombre = :nombre, 
        Apellido = :apellidos, 
        Fecha_Nacimiento = :fecha_nacimiento, 
        Numero_Socio_IHA = :numero_socio_iha, 
        Cinturon = :cinturon, 
        Nivel = :nivel, 
        Pais = :pais, 
        Correo = :correo
    WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([
        ":nombre" => $nombre, 
        ":apellidos" => $apellidos, 
        ":fecha_nacimiento" => $fecha_nacimiento, 
        ":numero_socio_iha" => $numero_socio_iha, 
        ":cinturon" => $cinturon, 
        ":nivel" => $nivel, 
        ":pais" => $pais,
        ":correo" => $correo,
        ":id" => $id
    ])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Registro modificado correctamente.</p>\n";
    }
}

pie();
?>
