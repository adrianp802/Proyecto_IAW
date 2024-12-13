<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Añadir 1");

// Mostramos el formulario
?>
<form action="insertar-2.php" method="post">
  <p>Escriba los datos del nuevo registro:</p>

  <table>
  <tr>
  <td>Nombre:</td>
  <td><input type="text" name="Nombre" autofocus></td>
</tr>
<tr>
  <td>Apellidos:</td>
  <td><input type="text" name="Apellido"></td>
</tr>
<tr>
  <td>Fecha de Nacimiento:</td>
  <td><input type="date" name="Fecha_Nacimiento"></td>
</tr>
<tr>
  <td>Número de Socio IHA:</td>
  <td><input type="number" name="Numero_Socio_IHA"></td>
</tr>
<tr>
  <td>Cinturón:</td>
  <td><input type="text" name="Cinturon" required></td>
</tr>
<tr>
  <td>Nivel:</td>
  <td>
    <select name="Nivel">
      <option value="pollito">Pollito</option>
      <option value="halcón">Halcón</option>
      <option value="águila">Águila</option>
      <option value="adulto">Adulto</option>
    </select>
  </td>
</tr>
<tr>
  <td>País:</td>
  <td><input type="text" name="Pais"></td>
</tr>
<tr>
  <td>Correo:</td>
  <td><input type="email" name="Correo" required></td>
</tr>
  </table>
  <p>
    <input type="submit" value="Añadir">
    <input type="reset" value="Reiniciar formulario">
  </p>
</form>
<?php

pie();
?>
