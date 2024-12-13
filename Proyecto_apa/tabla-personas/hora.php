<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Hora");

// Función para obtener el mensaje de bienvenida según la hora
function obtenerMensajeBienvenida() {
    $hora = date('H');

    if ($hora >= 6 && $hora < 12) {
        return "Buenos días";
    } elseif ($hora >= 12 && $hora < 18) {
        return "Buenas tardes";
    } else {
        return "Buenas noches";
    }
}

// Función para obtener la hora actual
function obtenerHoraActual() {
    return date('H:i:s');  // Muestra la hora en formato 24 horas
}

// Mostrar el mensaje de bienvenida y la hora actual
echo obtenerMensajeBienvenida() . ", la hora actual es: " . obtenerHoraActual();
pie();
?>
