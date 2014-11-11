<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

    if (isset($_SESSION['usuario'])) {
      $user_id = $_SESSION['usuario'];
    } else {
      _SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: ../usuarios/login.php");
    }

    $con = conectar();?>

    <table>
      <thead>
        <tr>
          <th>
          <p>NUEVO PEDIDO</p>
          </th>
        </tr>
        <tr>
          <th>
            <form action ="insertar.php" method="POST">
              <label for="articulo_id">Articulo: </label>
              <input type="text" name="articulo_id"><br>
              <label for="cantidad">Cantidad: </label>
              <input type="number" name="cantidad"><br>
              <input type="submit" value ="Añadir">
            </form>
          </th>
        </tr>
      </thead>
    </table>
  </body>
</html>