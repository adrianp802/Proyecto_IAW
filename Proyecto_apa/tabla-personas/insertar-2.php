<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Añadir 2");

$nombre            = recoge("Nombre");
$apellido          = recoge("Apellido");
$fecha_nacimiento  = recoge("Fecha_Nacimiento");
$numero_socio_iha  = recoge("Numero_Socio_IHA");
$cinturon          = recoge("Cinturon");
$nivel             = recoge("Nivel");
$pais              = recoge("Pais");
$correo            = recoge("Correo");

// Comprobamos que no se intenta crear un registro vacío
$registroNoVacioOk = false;

if ($nombre == "" && $apellido == "" && $numero_socio_iha == "" && $cinturon == "" && $nivel == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
} else {
    $registroNoVacioOk = true;
}

// Comprobamos que no se intenta crear un registro idéntico a uno que ya existe
$registroDistintoOk = false;

if ($registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM Alumno
    WHERE Nombre = :Nombre
    AND Apellido = :Apellido
    AND Fecha_Nacimiento = :Fecha_Nacimiento
    AND Numero_Socio_IHA = :Numero_Socio_IHA
    AND Cinturon = :Cinturon
    AND Nivel = :Nivel
    AND Pais = :Pais
    AND Correo = :Correo";

    $resultado = $pdo->prepare($consulta);
    if ($resultado && $resultado->execute([
        ":Nombre" => $nombre,
        ":Apellido" => $apellido,
        ":Fecha_Nacimiento" => $fecha_nacimiento,
        ":Numero_Socio_IHA" => $numero_socio_iha,
        ":Cinturon" => $cinturon,
        ":Nivel" => $nivel,
        ":Pais" => $pais,
        ":Correo" => $correo
    ])) {
        if ($resultado->fetchColumn() == 0) {
            $registroDistintoOk = true;
        } else {
            print "    <p class=\"aviso\">El registro ya existe. No se ha guardado el registro.</p>\n";
        }
    } else {
        print "    <p class=\"aviso\">Error al comprobar el registro existente. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    }
}

if ($registroNoVacioOk && $registroDistintoOk) {
    // Insertamos el registro en la tabla
    $consulta = "INSERT INTO Alumno 
    (Nombre, Apellido, Fecha_Nacimiento, Numero_Socio_IHA, Cinturon, Nivel, Pais, Correo)
    VALUES (:nombre, :apellido, :fecha_nacimiento, :numero_socio_iha, :cinturon, :nivel, :pais, :correo)";
    
    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":fecha_nacimiento" => $fecha_nacimiento,
        ":numero_socio_iha" => $numero_socio_iha,
        ":cinturon" => $cinturon,
        ":nivel" => $nivel,
        ":pais" => $pais,
        ":correo" => $correo
    ])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Registro creado correctamente.</p>\n";
    }
}
?>
