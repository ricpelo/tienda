<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar Clientes</title>
  </head>
  <body>
  <p><?= $id = 4?></p><?php


  $_SESSION['usuario'] = 1;

  function comprobar_usuario(){
      if (!isset($_SESSION['usuario']))
        header("Location: ../usuarios/login.php"); 
  }

  function obtener_cliente($con){

      if(isset($_GET['id'])){
        $id = trim($_GET['id']);  

        $res = pg_query_params($con, "select * 
                                 from clientes
                                where id = $1", [$id]);

        if(pg_num_rows($res) == 1)
          return $id;
        else
          throw new Exception("El cliente no existe");
      
      }
  }

  function comprobar_existe($id, $con){
  	$res = pg_query($con,"select id
  		                  	from clientes
  		                  where id::text = '$id'");

  	if (pg_num_rows($res) != 1)
  	{ 
    	throw new Exception("El cliente con el codigo $id no existe"); 
  	} 
  }

  function comprobar_borrado($res){
  	if (pg_affected_rows($res) != 1)
  	{
  		throw new Exception("No se ha podido borrar el cliente");
  	}
  }

  function pintar_cliente($id,$con){
    $res = pg_query($con,"select * 
                            from clientes
                          where id::text = '$id'"); 

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
        <form action="bajas.php" method="get">
          <input type="hidden" name="id" value="<?= $fila['id'] ?>">
          <p>¿Desea eliminar el usuario?</p>
          <input type="submit" value="Eliminar">
          <a href="index.php"><input type="button" value="Volver"></a>
        </form>
    </table><?php
  }

  require '../comunes/auxiliar.php';

  $con = conectar();
  $res = pg_query($con,"begin");
  $res = pg_query($con, "lock table clientes in share mode");

  if (isset($_GET['id']))
  {
  	$id = trim($_GET['id']);
  }

  try
  {
  	comprobar_existe($id,$con);
    pintar_cliente($id,$con);
    if(isset($_GET['id'])){
        $res = pg_query($con,"delete from clientes 
                        where id = $id");
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