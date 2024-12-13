<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Cálculo de Edad");

$fecha_nacimiento = recoge("Fecha_Nacimiento");
// Recoge la fecha de nacimiento enviada por el formulario
$fecha_actual = date('d-m-Y');

// Verificar si se ha recibido la fecha de nacimiento
if ($fecha_nacimiento) {
    // Convertir las fechas a objetos DateTime
    $fechaNacimiento = new DateTime($fecha_nacimiento);
    $fechaActual = new DateTime($fecha_actual);

    // Calcular la diferencia entre las dos fechas
    $diferencia = $fechaActual->diff($fechaNacimiento);

    // Mostrar la edad en años
    $edad = $diferencia->y;
    echo "Fecha actual: " . $fecha_actual . "<br>";
    echo "Fecha de nacimiento recibida: " . $fecha_nacimiento . "<br>";
    echo "La edad es: " . $edad . " años.";
} else {
    echo "Por favor, ingresa una fecha de nacimiento.";
} 
?>

