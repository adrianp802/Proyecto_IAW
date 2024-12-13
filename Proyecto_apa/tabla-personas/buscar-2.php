<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Buscar 2");

$nombre = recoge("nombre");

// Comprobamos los datos recibidos procedentes de un formulario
$nombreOk = false;
if ($nombre == "") {
    print "<p class=\"aviso\">El nombre proporcionado está vacío</p>";
} else {
    $nombreOk = true;
}

// Comprobamos si existen registros con las condiciones de búsqueda recibidas
$registrosEncontradosOk = false;

if ($nombreOk) {
    $consulta = "SELECT COUNT(*) FROM Alumno WHERE Nombre = :nombre;";  // 'Nombre' con mayúscula

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => "$nombre"])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">No se han encontrado registros.</p>\n";
    } else {
        $registrosEncontradosOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($nombreOk && $registrosEncontradosOk) {
    // Seleccionamos todos los registros con las condiciones de búsqueda recibidas
    $consulta = "SELECT * FROM Alumno WHERE Nombre = :nombre";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => "$nombre"])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
?>

<p>Registros encontrados:</p>

<table class="conborde franjas">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha de Nacimiento</th>
            <th>Número Socio IHA</th>
            <th>Cinturón</th>
            <th>Nivel</th>
            <th>País</th>
            <th>Correo</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($resultado as $registro) {
            print "<tr>\n";
            print "    <td>{$registro['Nombre']}</td>\n";  
            print "    <td>{$registro['Apellido']}</td>\n";  
            print "    <td>{$registro['Fecha_Nacimiento']}</td>\n";  
            print "    <td>{$registro['Numero_Socio_IHA']}</td>\n";  
            print "    <td>{$registro['Cinturon']}</td>\n";  
            print "    <td>{$registro['Nivel']}</td>\n";  
            print "    <td>{$registro['Pais']}</td>\n";
            print "    <td>{$registro['Correo']}</td>\n";  
            print "</tr>\n";
        }
        ?>
    </tbody>
</table>
<?php
    }
}

pie();
?>
