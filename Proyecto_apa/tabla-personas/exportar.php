<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Exportar datos a CSV");

// Consulta para obtener los datos sin los campos que no quieres exportar
$consulta = "SELECT Nombre, Apellido, Fecha_Nacimiento, Numero_Socio_IHA, Cinturon, Nivel, Pais, Correo FROM Alumno";
$resultado = $pdo->query($consulta);

if (!$resultado) {
    print "    <p class=\"aviso\">Error al realizar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    pie();
    exit;
}

$registros = $resultado->fetchAll(PDO::FETCH_ASSOC);

if (empty($registros)) {
    print "    <p class=\"aviso\">No hay datos para exportar.</p>\n";
    pie();
    exit;
}

// Crear un nombre para el archivo CSV
$nombreArchivo = "exportacion_alumnos_" . date("Ymd_His") . ".csv";

// Ruta del archivo CSV en el servidor
$rutaArchivo = "../archivos_csv/" . $nombreArchivo;

// Abrir el archivo CSV para escritura
$output = fopen($rutaArchivo, "w");

// Escribir la fila de encabezados
fputcsv($output, array_keys($registros[0]));

// Escribir los registros
foreach ($registros as $registro) {
    fputcsv($output, $registro);
}

fclose($output);

// Mostrar enlace para descargar el archivo CSV
print "<p>Haz clic en el siguiente icono para descargar el archivo CSV:</p>\n";
print "<a href=\"$rutaArchivo\" download><img src='ruta_a_icono_de_descarga.png' alt='Descargar CSV' /></a>";

pie();
?>
