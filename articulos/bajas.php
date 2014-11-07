<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Baja de Articulos</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

    $con = conectar();

    $_SESSION['usuario'] = 1;

    function comprobar_usuario(){
      if (!isset($_SESSION['usuario']))
        header("Location: ../usuarios/login.php");  
    }

    function obtener_usuario(){
      $usuario = (isset($_SESSION['usuario'])) ?
                                        (int) trim($_SESSION['usuario']) : "";
      return $usuario;
    }

    $usuario = obtener_usuario();
  ?>

    <?= $usuario ?>
  </body>
</html>