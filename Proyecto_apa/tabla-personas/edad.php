<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Edad");
?>

<form action="edad2.php" method="post" >
<tr>
  <td>Fecha de Nacimiento:</td>
  <td><input type="date" name="Fecha_Nacimiento"></td>
</tr>
<button type="submit">Calcular Edad</button>
    </form>

<?php
pie();
?>