<?php

  function conectar()
  {
   return pg_connect("host=localhost user=tienda password=tienda
                       dbname=tienda");
  }

  function comprobar_usuario()
  {
    if (!isset($_SESSION['usuario'])) {
      $_SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: /tienda/usuarios/login.php");
    }
    return $_SESSION['usuario'];
  }

  function comprobar_nick($id)
  {
    $con = conectar();

    $res = pg_query($con, "select nick from usuarios where id::text = '$id'");

    if (pg_num_rows($res) == 1):
      $fila = pg_fetch_assoc($res, 0);
      $nick = $fila['nick'];
    endif;

    pg_close();

    if (isset($nick)):
      return $nick; 
    else:
      $_SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: /tienda/usuarios/login.php");
    endif;
  }