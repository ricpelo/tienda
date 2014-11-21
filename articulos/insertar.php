<?php session_start() ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar artículos</title>
</head>
<body><?php
    require '../comunes/auxiliar.php';


    //$_SESSION['usuario'] = 1;


    $errores = array();

    function comprobar_usuario()
    {
      if (!(isset($_SESSION['usuario'], $_SESSION['rol']) && $_SESSION['rol'] == 1)) {
        header("Location: ../usuarios/login.php");
      }
    }

    function parametro_no_vacio($par, $hum)
    {
      global $errores;

      if ($par == "")
      {
        $errores[] = "El campo $hum es obligatorio";
        return false;
      }

      return true;
    }

    function comprobar_codigo($codigo)
    {
        global $errores;

        if (parametro_no_vacio($codigo, "Código"))
        {
            if (is_numeric($codigo))
            {
                if (strlen($codigo) > 13)
                {
                    $errores[] = "El código no puede sobrepasar los 13 dígitos";
                }
            } else {
                $errores[] = "No ha introducido un número en el campo Código";
            }
        }
    }

    function comprobar_descripcion($descripcion)
    {
      global $errores;

      if (parametro_no_vacio($descripcion, "Descripción"))
      {
        if (strlen($descripcion) > 50)
        {
          $errores[] = "La descripcion es demasiado larga";
        }
      }
    }

    function comprobar_precio($precio)
    {
        global $errores;

        if (is_numeric($precio))
        {
            $num = explode(".", $precio);

            if (strlen($num[0]) <= 4 && strlen($num[1]) <= 2)
            {
                return;
            }
            else
            {
                $errores[] = "El formato del campo Precio no es válido";
            }
        }
        else
        {
            $errores[] = "No ha introducido un número en el campo Precio";
        }
    }

    function comprobar_existencias($existencias)
    {
        global $errores;

        if (!is_numeric($existencias))
        {
            $errores[] = "No ha introducido una cantidad correcta en el campo Cantidad";
        }
    }

    function comprobar_errores()
    {
      global $errores;
      
      if (!empty($errores))
      {
        throw new Exception();
      }
    }

    function comprobar_si_existe($codigo, $con)
    {
      global $errores;

      $res = pg_query_params($con, "select codigo
                             from articulos
                             where codigo = $1", array($codigo));

      if (pg_num_rows($res) > 0)
      {
        $errores[] = "El artículo $codigo ya existe.";
        comprobar_errores();
      }
    }

    function comprobar_insercion($res)
    {
      global $errores;

      if ($res == FALSE || pg_affected_rows($res) != 1)
      {
        $errores[] = "No se ha podido insertar el artículo";
        comprobar_errores();
      }
    }

    comprobar_usuario();
    if (isset($_POST['codigo'], $_POST['descripcion'], $_POST['precio'], 
        $_POST['existencias']))
    {
        $codigo         = trim($_POST['codigo']);
        $descripcion    = trim($_POST['descripcion']);
        $precio         = trim($_POST['precio']);
        $existencias    = trim($_POST['existencias']);
        $existencias_2  = $existencias;
        if ($existencias_2 == "") {
            $existencias_2 = 0;
        }

        try {
            comprobar_codigo($codigo);
            comprobar_descripcion($descripcion);
            comprobar_precio($precio);
            comprobar_existencias($existencias);
            comprobar_errores();


            $con = conectar();
            $res = pg_query($con, "begin");
            $res = pg_query($con, "lock table articulos in share mode");


            comprobar_si_existe($codigo, $con);


            $res = pg_query_params($con, "insert into articulos (codigo, descripcion, precio, existencias) values ($1, $2, $3, $4)", array($codigo, $descripcion, $precio, $existencias_2));


            comprobar_insercion($res); ?>


            <p>Artículo insertado</p>
            <a href="insertar.php"><input type="button" value="Continuar insertando"/></a>
            <a href="index.php"><input type="button" value="Volver"/></a><?php
            goto fin;
        } catch (Exception $e) {
            foreach ($errores as $error) { ?>
                <p>Error: <?= $error ?></p><?php
            }
        } finally {
            if (isset($con) && $con != FALSE)
            {
                $res = pg_query($con, "commit");
                pg_close($con);
            }
        }
        
    }
    else
    {
        $codigo = $descripcion = $precio = $existencias = "";
    } ?>

    <form action="insertar.php" method="post">
        <label for="codigo">Código:*</label>
        <input type="text" name="codigo" value="<?= $codigo ?>" size="13"><br/>
        <label for="descripcion">Descripción:*</label>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($descripcion) ?>" size="50"><br/>
        <label for="precio">Precio:*</label>
        <input type="text" name="precio" value="<?= $precio ?>"><br/>
        <label for="existencias">Existencias:</label>
        <input type="text" name="existencias" value="<?= $existencias ?>"><br/>
        <input type="submit" value="Insertar">
        <a href="index.php"><input type="button" value="Volver"/></a>
    </form><?php

fin: ?>
</body>
</html>