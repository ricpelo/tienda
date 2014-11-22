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
      header("Location: ../usuarios/login.php");
    }
    return $_SESSION['usuario'];
  }