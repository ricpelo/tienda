<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
	</head>
	<body>

		<?php 
		require '../comunes/auxiliar.php';

		
		$errores = array();
		$id = 1;

		
		function comprobar_modificacion($res)
		{
			global $errores;

      if ($res == FALSE || pg_affected_rows($res) != 1)
      {
        $errores[] = "No se ha podido modificar el cliente";
        comprobar_errores();
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
		
		function comprobar_cadena_vacia($codigo, $nombre, $apellidos, $dni)
		{
			global $errores;
			$codigo == "" ? $errores[] = "El codigo del cliente esta vacio" : "";
			$nombre == "" ? $errores[] = "El nombre del cliente esta vacio" : "";
			$apellidos == "" ? $errores[] = "Los apellidos del cliente estan vacio" : "";
			$dni == "" ? $errores[] = "El dni del cliente esta vacio" : "";

				
		}

	



	



	






	



	







		if(isset($_POST['codigo'], $_POST['nombre'], $_POST['apellidos'], $_POST['dni']))
		{
			$codigo = trim($_POST['codigo']);
			$nombre = trim($_POST['nombre']);
			$apellidos = trim($_POST['apellidos']);
			$dni = trim($_POST['dni']);
			$direccion = trim($_POST['direccion']);
			$poblacion = trim($_POST['poblacion']);
			$codigo_postal = trim($_POST['codigo_postal']);
			$id = trim($_POST['id']);

			/* HACER COMPROBACIONES*/

			try {
    
        
        comprobar_cadena_vacia($codigo, $nombre, $apellidos, $dni);
        comprobar_errores();
        $con   = conectar();
        $res   = pg_query($con, "begin");
        $res   = pg_query($con, "lock table clientes in share mode");
     
        	$res   = pg_query($con, "update clientes
                               		 set codigo        = $codigo,
                               		     nombre   		 = '$nombre',
                                       apellidos     = '$apellidos',
                                       dni 					 = '$dni',
                                       direccion     = '$direccion',
                                   		 poblacion     = '$poblacion',
                                       codigo_postal = '$codigo_postal'
                               			where id::text = '$id'");
        comprobar_modificacion($res); ?>
        <script language="javascript">alert("Se ha modificado el cliente correctamente");</script>
        <a href="index.php"><input type="button" value="Volver" /></a>
        <?php
        goto fin; 
      } catch (Exception $e) {
        foreach ($errores as $error): ?>
          <p>Error: <?= $error ?></p><?php
        endforeach;
      } finally {
        if (isset($con) && $con != FALSE)
        {
          $res = pg_query($con, "commit");
          pg_close($con);
        }
      }











    }









			/*******************************/

		

			if(isset($_POST['id']))
    {
    	$codigo = trim($_POST['codigo']);
			$nombre = trim($_POST['nombre']);
			$apellidos = trim($_POST['apellidos']);
			$dni = trim($_POST['dni']);
			$direccion = trim($_POST['direccion']);
			$poblacion = trim($_POST['poblacion']);
			$codigo_postal = trim($_POST['codigo_postal']);
			$id = trim($_POST['id']);
    }
			
     
	



		else if (isset($id))
		{
			$clientes_id = trim($id);
			$con = conectar();
			$res = pg_query($con, "select * from clientes
                               		  where id::text = '$clientes_id'");
			if (pg_num_rows($res) > 0)
      {
        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
      }
    }
		?>



    	
			<h3>Modificar cliente</h3>
			<form action="modificar.php" method="POST">
			<label for="codigo">Codigo *:</label>
			<input type="text" name="codigo" value="<?= $codigo ?>" /><br><br>
			<label for="nombre">Nombre *: </label>
			<input type="text" name="nombre" value="<?= $nombre ?>" /><br><br>
			<label for="apellidos">Apellidos *: </label>
			<input type="text" name="apellidos" value="<?= $apellidos ?>" /><br><br>
			<label for="dni">DNI *:</label>
			<input type="text" name="dni" value="<?= $dni ?>" /><br><br>
			<label for="direccion">Direccion:</label>
			<input type="text" name="direccion" value="<?= $direccion ?>" /><br><br>
			<label for="poblacion">Poblacion:</label>
			<input type="text" name="poblacion" value="<?= $poblacion ?>" /><br><br>
			<label for="codigo_postal">Código Postal:</label>
			<input type="text" name="codigo_postal" value="<?= $codigo_postal ?>" /><br><br>
			<input type="hidden" name="id" value="<?= $id ?>" /><br><br>
			<input type="submit" value="Modificar" />
			<a href="index.php"><input type="button" value="Volver" /></a>
			</form>
			<?php
		
			//pg_close($con);
			fin:

		?>



		

		
	</body>
</html>