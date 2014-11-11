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
    $errores[];

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

    function obtener_articulo(){
      global $con;
      global $errores;

      if (isset($_GET['id']):
        $id = $_GET['id'];  

        $res = pg_query($con, "select * 
                               from articulos
                              where id::text = '$id'");

        if(pg_num_rows($res) == 1)
          return $res;
      
      else:
        header("Location: ../index.php"); 
      endif;
    }

    function borrar_articulo($id){
      global $con;
      global $errores;

      $res = pg_query($con, "delete from articulos
                              where id::text = '$id'");
    }


    comprobar_usuario();
    obtener_articulo();
    borrar_articulo();

  ?>
  </body>
</html>