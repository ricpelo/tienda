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
    $errores = [];

    $_SESSION['usuario'] = 1;

    function comprobar_usuario(){
      if (!isset($_SESSION['usuario']))
        header("Location: ../usuarios/login.php"); 
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

    function pintar_articulo($id){
      global $con;
        
      $res = pg_query($con, "select * 
                               from articulos
                              where id::text = '$id'");

      $fila = pg_fetch_assoc($res); ?>
        
        <table border="1">
          <thead>
            <th>
              Código      
            </th>
            <th>
              Descripción
            </th>
            <th>
              Precio
            </th>
            <th>
              Existencias
            </th>
          </thead>
          <tbody>
            <tr>
              <td>
                <?= $fila['codigo'] ?>
              </td>
              <td>
                <?= $fila['descripcion'] ?>
              </td>
              <td>
                <?= $fila['precio'] ?>
              </td>
              <td>
                <?= $fila['existencias'] ?>
              </td>
            </tr>
          </tbody>
        </table>
        <form action="bajas.php" method="post">
          <input type="hidden" name="id" value="<?= $fila['id'] ?>">
          <h3>¿Seguro que desea eliminar el artículo?</h3>
          <input type="submit" value="Eliminar">
        </form>

        <?php
    }

    function obtener_articulo(){
      global $con;
      global $errores;

      if(isset($_GET['id'])){
        $id = trim($_GET['id']);  

        $res = pg_query($con, "select * 
                                 from articulos
                                where id::text = '$id'");

        if(pg_num_rows($res) == 1)
          return $id;
        else
          $errores[] = "El articulo no existe";
      
      }

      comprobar_errores();
    }

    function borrar_articulo($id){
      global $con;
      global $errores;

      $res = pg_query($con, "delete from articulos
                              where id::text = '$id'");
    }

    try{
      comprobar_usuario();
      $res = pg_query($con, "begin");
      $res = pg_query($con, "lock table tienda in share mode");

      if(isset($_POST['id'])){
        borrar_articulo($_POST['id']); 
        header("Location: index.php");
      }else{
        pintar_articulo(obtener_articulo()); ?>
        <a href="index.php"><button>Volver</button></a> <?php
      }
    }catch(Exception $e){
      foreach ($errores as $v) { ?>
        <p><?= $v ?></p> <?php
      } ?>
      <a href="index.php"><button>Volver</button></a> <?php

    }finally {
      $res = pg_query($con, "commit");
      pg_close($con);
    }
  ?>
  </body>
</html>