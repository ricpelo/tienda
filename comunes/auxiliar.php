<?php

  function conectar()
  {
   return pg_connect("host=localhost user=tienda password=tienda
                       dbname=tienda");
  }

  function comprobar_usuario()
{
    if (!(isset($_SESSION['usuario'], $_SESSION['rol']) && $_SESSION['rol'] == 1)) {
        header("Location: ../usuarios/login.php");
    }
}