<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

cabecera("Personas - Borrar todo 1");
?>

    <form action="borrar-todo-2.php" method="get">
      <p>¿Está seguro?</p>

      <p>
        <input type="submit" name="borrar" value="Sí">
        <input type="submit" name="borrar" value="No">
      </p>
    </form>

<?php
pie();
?>
