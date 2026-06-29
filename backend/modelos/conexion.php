<?php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";
$db       = "motodynamiq";

  $conexion = mysqli_connect($servidor,$usuario,$clave,"") or die("No encontro el servidor");
  mysqli_select_db($conexion, $db) or die("No encontro la base de datos");
  mysqli_set_charset($conexion,"utf8");

  // echo"Se conecto correctamenete";

?>

