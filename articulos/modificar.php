<?php session_start()?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Modificar Artículo</title>
</head>
<body><?php
		require '../comunes/auxiliar.php';

		$errores = array();

		function comprobar_id($id)
		{
			global $errores;

			$con = conectar();

			$res = pg_query_params($con, "select *
                               from articulos
                              where id::text = $1", array($id));

			if (pg_num_rows($res) < 1)
			{
				header("Location: ../articulos/index.php");
				//$errores[] = "El articulo no existe";
			}
		}

		function comprobar_codigo($codigo)
    {
      global $errores;

      if (filter_var($codigo, FILTER_VALIDATE_FLOAT,
                       array(
                         'options' => array(
                                        'min_range' => -9999999999999,
                                        'max_range' => 9999999999999))) !== FALSE)
        {
          return;
        }

        $errores[] = "El código no es válido. Debe ser un numero de 13 digitos";
      
    }

    function comprobar_descripcion($descripcion)
    {
    	global $errores;

    	if (strlen($descripcion) > 50)
    	{
    		$errores[] = "La descripcion es demasiado larga";
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
    			$errores[] = "El formato del precio no es válido";
    		}
    	}
    	else
    	{
    		$errores[] = "Debe introducir un número para el precio";
    	}
    }

    function comprobar_existencias($existencias)
    {
    	global $errores;

    	if (is_numeric($existencias) && $existencias >= 0)
    	{
    		return;
    	}
      $errores[] = "Las existencias introducidas no son validas";
      
    }

    function comprobar_errores()
    {
      global $errores;
      
      if (!empty($errores))
      {
        throw new Exception();
      }
    }

    function comprobar_modificacion($res)
    {
      global $errores;

      if ($res == FALSE || pg_affected_rows($res) != 1)
      {
        $errores[] = "No se ha podido modificar el articulo";
        comprobar_errores();
      }
    }

    function volver()
    { 
      return '<a href="index.php"><input type="button" value="Volver" /></a>';
    }


    if (isset($_GET['id']))
    {
      $id = $_GET['id'];

      $con = conectar();

      $res = pg_query_params($con, "select *
                               from articulos
                               where id::text = $1", array($id));
      comprobar_id($id);
      $fila = pg_fetch_assoc($res);
      extract($fila);
    }
    elseif (isset($_POST['id']))
    {
      extract($_POST);

      try
      {
        comprobar_id($id);
        comprobar_codigo($codigo);
        comprobar_descripcion($descripcion);
        comprobar_precio($precio);
        comprobar_existencias($existencias);
        comprobar_errores();
        
        $con = conectar();
        $res   = pg_query($con, "begin");
        $res   = pg_query($con, "lock table articulos in share mode");
        $res   = pg_query_params($con, "update articulos set 
                                                  codigo       = $1,
                                                  descripcion  = $2, 
                                                  precio       = $3,
                                                  existencias  = $4
						  where id     = $5",
						  array($codigo, $descripcion,
						  $precio, $existencias, $id));

        comprobar_modificacion($res);?>
        <p>El articulo  <?= $descripcion ?> se ha modificado correctamente.</p><?php
        volver();
      } catch (Exception $e) {
        foreach ($errores as $error): ?>
          <p>Error: <?= $error ?></p><?php
        endforeach;
	  } finally {
			  if (isset($con)) {
					  $res = pg_query($con, "commit");
			  }
      }
    }?>

		<form action="modificar.php" method="post">
			<input type="hidden" name="id" value="<?= $id ?>">
			<label for="codigo">Código:</label>
      <input type="text" name="codigo" value="<?= $codigo ?>" size="13" /><br/>
      <label for="descripcion">Descripción:</label>
      <input type="text" name="descripcion" value="<?= htmlspecialchars($descripcion) ?>" size="50"/><br/>
      <label for="precio">Precio:</label>
      <input type="text" name="precio" value="<?= $precio ?>" /><br/>
      <label for="existencias">Existencias:</label>
      <input type="text" name="existencias" value="<?= $existencias ?>" /><br/>
			<input type="submit" value="Modificar" />
		</form>
   <?= volver() ?>

</body>
</html>
