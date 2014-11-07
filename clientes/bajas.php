<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar</title>
  </head>
  <body>
  <p><?= $codigo = 100;?><p><?php

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