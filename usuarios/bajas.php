<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar Usuarios</title>
  </head>
  <body>
  <p><?= $id = '2';?><p><?php
  	
  if (isset($_SESSION['id']))
  {
  	$id = trim($_POST['id']);
  }

  function comprobar_existe($id, $con){
  	$res = pg_query($con,"select id
  		                  	from usuarios
  		                  where id = '$id'");

  	if (pg_num_rows($res) != 1)
  	{ 
    	throw new Exception("El usuario con el id $id no existe"); 
  	} 
  }

  function comprobar_borrado($res){
  	if (pg_affected_rows($res) != 1)
  	{
  		throw new Exception("No se ha podido borrar el usuario");
  	}
  }

  function pintar_usuarios($id,$con){
  	$res = pg_query($con,"select * 
  		  					from usuarios
  		  				  where id = '$id'");

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
          <input type="hidden" name="id" value="<?= $fila['id'] ?>">
          <p>¿Desea eliminar el usuario?</p>
          <input type="submit" value="Eliminar">
          <a href="index.php"><input type="button" value="Volver"></a>
        </form>
    </table><?php
  }

  if (isset($_POST['id']))
  {
  	$id = trim($_POST['id']);
  }

  require '../comunes/auxiliar.php';

  $con = conectar();
  $res = pg_query($con,"begin");
  $res = pg_query($con, "lock table usuarios in share mode");

  try
  {
  	comprobar_existe($id,$con);
  	pintar_usuarios($id,$con);
  	if(isset($_POST['id'])){
  		$res = pg_query($con,"delete from usuarios
  		                  where id = '$id'");
  		comprobar_borrado($res); ?>
  		<p>El cliente se ha borrado correctamente</p><?php
  		header("Location: index.php");
    }
  }catch(Exception $e) { ?>
  	<p>Error:<?= $e->getMessage() ?></p><?php
  }

  $res = pg_query($con,"commit");
  pg_close($con);


  ?>
  </body>
</html>