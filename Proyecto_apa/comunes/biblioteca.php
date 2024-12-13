<?php
// Carga Biblioteca específica de la base de datos utilizada

function recoge($key, $type = "", $default = null, $allowed = null)
{
    if (!is_string($key) && !is_int($key) || $key == "") {
        trigger_error("Function recoge(): Argument #1 (\$key) must be a non-empty string or an integer", E_USER_ERROR);
    } elseif ($type !== "" && $type !== []) {
        trigger_error("Function recoge(): Argument #2 (\$type) is optional, but if provided, it must be an empty array or an empty string", E_USER_ERROR);
    } elseif (isset($default) && !is_string($default)) {
        trigger_error("Function recoge(): Argument #3 (\$default) is optional, but if provided, it must be a string", E_USER_ERROR);
    } elseif (isset($allowed) && !is_array($allowed)) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (is_array($allowed) && array_filter($allowed, function ($value) { return !is_string($value); })) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (!isset($default) && isset($allowed) && !in_array("", $allowed)) {
        trigger_error("Function recoge(): If argument #3 (\$default) is not set and argument #4 (\$allowed) is set, the empty string must be included in the \$allowed array", E_USER_ERROR);
    } elseif (isset($default, $allowed) && !in_array($default, $allowed)) {
        trigger_error("Function recoge(): If arguments #3 (\$default) and #4 (\$allowed) are set, the \$default string must be included in the \$allowed array", E_USER_ERROR);
    }

    if ($type == "") {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = "";
        } else {
            $tmp = trim(htmlspecialchars($_REQUEST[$key]));
        }
        if ($tmp == "" && !isset($allowed) || isset($allowed) && !in_array($tmp, $allowed)) {
            $tmp = $default ?? "";
        }
    } else {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = [];
        } else {
            $tmp = $_REQUEST[$key];
            array_walk_recursive($tmp, function (&$value) use ($default, $allowed) {
                $value = trim(htmlspecialchars($value));
                if ($value == "" && !isset($allowed) || isset($allowed) && !in_array($value, $allowed)) {
                    $value = $default ?? "";
                }
            });
        }
    }
    return $tmp;
}
/* 
Esta función pinta la parte superior de las páginas web
SI LA SESIÓN ESTÁ INICIADA: Saca el menú de las funciones que se pueden hacer en la base de datos + DESCONECTARSE
SI LA SESIÓN NO ESTÁ INICIADA: Saca exclusivamente el menu CONECTARSE 
*/
function cabecera($texto)
{
    print "<!DOCTYPE html>\n";
    print "<html lang=\"es\">\n";
    print "<head>\n";
    print "  <meta charset=\"utf-8\">\n";
    print "  <title>\n";
    print "    $texto";
    print "  </title>\n";
    print "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    print "  <link rel=\"stylesheet\" href=\"1.css\" title=\"Color\">\n";
    print "</head>\n";
    print "\n";
    print "<body>\n";
    print"<img src=\" logo-jeonsa.png\">\n";
    print "  <header>\n";
    print "    <h1>Hapkido - $texto</h1>\n";
    print "\n";
    print "    <nav>\n";
    print "      <ul>\n";
    if (!isset($_SESSION["conectado"])) {
      
            print "        <li><a href=\"login-1.php\">Conectarse</a></li>\n";
       
        } 
       
     else {
        print "        <li><a href=\"insertar-1.php\" class=\"button\">Añadir registro</a></li>\n"; 
        print "        <li><a href=\"listar.php\" class=\"button\">Listar</a></li>\n";
        print "        <li><a href=\"borrar-1.php\" class=\"button\">Borrar</a></li>\n";
        print "        <li><a href=\"buscar-1.php\" class=\"button\">Buscar</a></li>\n";
        print "        <li><a href=\"modificar-1.php\" class=\"button\">Modificar</a></li>\n";
        print "        <li><a href=\"borrar-todo-1.php\" class=\"button\">Borrar todo</a></li>\n";
        print "        <li><a href=\"../logout.php\" class=\"button\">Desconectarse</a></li>\n"; 
        print "        <li><a href=\"exportar.php\" class=\"button\">Exportar registros</a></li>\n";  
        print "        <li><a href=\"hora.php\" class=\"button\">Hora</a></li>\n";       
        print "        <li><a href=\"edad.php\" class=\"button\">Calcula la Edad</a></li>\n";
        } 
    print "      </ul>\n";
    print "    </nav>\n";
    print "  </header>\n";
    print "\n";
    print "  <main>\n";
}


function pie() {
    print "<p class=\"pf\"> &copy; CHKN ADRIAN PAVON</p>\n";
}


// Funciones BASES DE DATOS
function conectaDb()
{ 

    try {
        $tmp = new PDO("mysql:host=localhost;dbname=Proyecto_apa;charset=utf8mb4", "adrianp", "usuario");
       return $tmp;
    }  catch (PDOException $e) {
        print "    <p class=\"aviso\">Error: No puede conectarse con la base de datos. {$e->getMessage()}</p>\n";
    } 

}

// MYSQL: Borrado y creación de base de datos y tablas

function borraTodo()
{
    global $pdo;

    print "    <p>Sistema Gestor de Bases de Datos: MySQL.</p>\n";
    print "\n";

    $consulta = "DROP DATABASE IF EXISTS Proyecto_apa";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error al borrar la base de datos. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Base de datos borrada correctamente (si existía).</p>\n";
    }
    print "\n";

    $consulta = "CREATE DATABASE Proyecto_apa
                 CHARACTER SET utf8mb4
                 COLLATE utf8mb4_unicode_ci";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error al crear la base de datos. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Base de datos creada correctamente.</p>\n";
        print "\n";

        $consulta = "USE Proyecto_apa";

        if (!$pdo->query($consulta)) {
            print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } else {
            print "    <p>Base de datos seleccionada correctamente.</p>\n";
            print "\n";

            $consulta = "CREATE TABLE Alumno (
                id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
                Nombre VARCHAR(50) NOT NULL,
                Apellido VARCHAR(50) NOT NULL,
                Fecha_Nacimiento DATE, 
                Numero_Socio_IHA INT UNIQUE NOT NULL,
                Cinturon VARCHAR(50) NOT NULL,
                Nivel ENUM('pollito', 'halcón', 'águila', 'adulto') NOT NULL,
                Pais VARCHAR(50),
                Correo VARCHAR(100)
)";


            if (!$pdo->query($consulta)) {
                print "    <p class=\"aviso\">Error al crear la tabla. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p>Tabla creada correctamente.</p>\n";
            }
        }
    }
}

function encripta($cadena)
{
    

    return hash("sha256", $cadena);
}
