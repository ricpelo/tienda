<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Tienda</title>
  </head>
  <body><?php
     require 'comunes/auxiliar.php';

    $con = conectar();
    $errores = [];

    $_SESSION['usuario'] = 9;

    function comprobar_usuario(){
      if (!isset($_SESSION['usuario']))
        header("Location: usuarios/login.php"); 
    }

    function comprobar_admin(){
      global $con;

      $usuario = trim($_SESSION['usuario']);

      $res = pg_query_params($con, "select *
                               from usuarios
                              where id = $1", [$usuario]);

      if (pg_num_rows($res) == 1){
        $fila = pg_fetch_assoc($res);

        if($fila['rol_id'] != 1){
          $errores[] = "Error: Usuario sin permisos.";

          comprobar_errores();
        }
      }
    }

    function comprobar_errores(){
      global $errores;

      if(!empty($errores))
        throw new Exception();    
    }

    function obtener_usuario(){
      $usuario = (isset($_SESSION['usuario'])) ?
                                        (int) trim($_SESSION['usuario']) : "";
      return $usuario;
    }

    function limpiar_datos(){
      foreach ($_POST as $key => $value)
        $_POST["$key"] = trim($value);
    } ?>
  </body>
</html>