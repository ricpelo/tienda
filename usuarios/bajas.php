<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar Usuarios</title>
  </head>
  <body>
  <p><?= $nick = 'pepe';?><p><?php
  	
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

  function comprobar_borrado($res){
  	if (pg_affected_rows($res) != 1)
  	{
  		throw new Exception("No se ha podido borrar el usuario");
  	}
  }

  function pintar_usuarios($nick,$con){
  	$res = pg_query($con,"select * 
  		  					from usuarios
  		  				  where nick = '$nick'");

  	$cols = array('nick' => 'Nick',
  		          'password' => 'Contraseña',
  		          'rol_id' => 'Id rol'); ?>

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
  	pintar_usuarios($nick,$con);
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