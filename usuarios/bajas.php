<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar Usuarios</title>
  </head>
  <body>
  <p><?= $nick = 'Maria';?><p><?php
  	
  if (isset($_SESSION['nick']))
  {
  	$nick = trim($_POST['nick']);
  }

  function comprobar_existe($nick, $con){
  	$res = pg_query($con,"select nick
  		                  	from usuarios
  		                  where nick = '$nick'");

  	if (pg_num_rows($res) != 1)
  	{ 
    	throw new Exception("El usuario con el nick $nick no existe"); 
  	} 
  }

  function comprobar_si_cliente($nick,$con){
    $res = pg_query($con,"select nick 
                            from usuarios
                          where rol_id = 2");

    if (pg_num_rows($res) = 1)
    { 
      throw new Exception("El usuario con el nick $nick no puede borrarse porque es un cliente"); 
    } 
  }

  function comprobar_borrado($res){
  	if (pg_affected_rows($res) != 1)
  	{
  		throw new Exception("No se ha podido borrar el usuario");
  	}
  }

  if (isset($_POST['nick']))
  {
  	$nick = trim($_POST['nick']);
  }

  require '../comunes/auxiliar.php';

  $con = conectar();
  $res = pg_query($con,"begin");
  $res = pg_query($con, "lock table usuarios in share mode");

  try
  {
  	comprobar_existe($nick,$con);
    comprobar_si_cliente($nick,$con);
  	$res = pg_query($con,"delete from usuarios
  		                  where nick = '$nick'");
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