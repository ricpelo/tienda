<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar</title>
  </head>
  <body>
  <p><?= $codigo = 110001;?><p><?php

  if (isset($_SESSION['codigo']))
  {
  	$codigo = trim($_POST['codigo']);
  }

  function comprobar_existe($codigo, $con){
  	$res = pg_query($con,"select codigo
  		                  	from clientes
  		                  where codigo::text = '$codigo'");

  	if (pg_num_rows($res) != 1)
  	{ 
    	throw new Exception("El cliente con el codigo $codigo no existe"); 
  	} 
  }

  function comprobar_borrado($res){
  	if (pg_affected_rows($res) != 1)
  	{
  		throw new Exception("No se ha podido borrar el cliente");
  	}
  }

  function pintar_cliente($codigo,$con){
    $res = pg_query($con,"select * 
                            from clientes
                          where codigo::text = '$codigo'"); 

    $fila = pg_fetch_assoc($res); 

    $cols = array('codigo' => 'Codigo',
                  'nombre' => 'Nombre',
                  'apellidos'     => 'Apellidos',
                  'dni' => 'DNI',
                  'direccion' => 'Dirección',
                  'poblacion' => 'Población',
                  'codigo_postal' => 'Código postal',
                  'usuario_id' => 'Id usuarios'); ?>

    <table border="1">
      <thead><?php
        foreach ($cols as $k => $v) : ?> 
          <th><?= $v ?></th><?php
        endforeach; ?>
      </thead>
          <tbody><?php
            for ($i = 0; $i < pg_num_rows($res); $i++): 
              $fila = pg_fetch_assoc($res,$i); ?>
              <tr><?php
                 foreach ($cols as $k => $v) : ?> 
                    <td><?= $fila[$k] ?></td><?php
                 endforeach; ?>    
              </tr><?php
            endfor; ?>
          </tbody>
        </table>
        <form action="bajas.php" method="post">
          <input type="hidden" name="codigo" value="<?= $fila['codigo'] ?>">
          <p>¿Desea eliminar el artículo?</p>
          <input type="submit" value="Eliminar">
          <a href="index.php"><input type="button" value="Volver"></a>
        </form>
    </table><?php
  }

  if (isset($_POST['codigo']))
  {
  	$codigo = trim($_POST['codigo']);
  }

  require '../comunes/auxiliar.php';

  $con = conectar();
  $res = pg_query($con,"begin");
  $res = pg_query($con, "lock table clientes in share mode");

  try
  {
  	comprobar_existe($codigo,$con);
    pintar_cliente($codigo,$con);
  	$res = pg_query($con,"delete from clientes 
  		                  where codigo = $codigo");
  	comprobar_borrado($res); ?>
  	<p>El cliente se ha borrado correctamente</p><?php
  }catch(Exception $e) { ?>
  	<p>Error:<?= $e->getMessage() ?></p><?php
  }

  $res = pg_query($con,"commit");
  pg_close($con);

  ?>
  </body>
</html>