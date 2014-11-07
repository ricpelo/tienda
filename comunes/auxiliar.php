<?php

  function conectar()
  {
    return pg_connect("host=localhost user=tienda password=tienda
                       dbname=tienda");
  }
?>  