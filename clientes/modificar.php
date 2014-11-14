<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
	</head>
	<body>

		<?php 
		require '../comunes/auxiliar.php';

		$con = conectar();

		$id = 1;

		
		
			

		
		

	



	



	






	



	







		if(isset($_POST['codigo'], $_POST['nombre'], $_POST['apellidos'], $_POST['dni']) && $_POST['codigo'] != "")
		{
			$codigo = trim($_POST['codigo']);
			$nombre = trim($_POST['nombre']);
			$apellidos = trim($_POST['apellidos']);
			$dni=trim($_POST['dni']);
			$direccion = trim($_POST['direccion']);
			$poblacion = trim($_POST['poblacion']);
			$codigo_postal = trim($_POST['codigo_postal']);
			$id = trim($_POST['id']);

			/* HACER COMPROBACIONES*/





			/*******************************/

			$res   = pg_query($con, "update clientes
                               set codigo        = $codigo,
                               		 nombre   		 = '$nombre',
                                   apellidos     = '$apellidos',
                                   dni 					 = '$dni',
                                   direccion     = '$direccion',
                                   poblacion     = '$poblacion',
                                   codigo_postal = '$codigo_postal'
                               where id::text = '$id'");

			echo '<script language="javascript">alert("Se ha modificado correctamente");</script>';
     
		}
		else{echo 'noo';}



		if (isset($id))
		{
			$clientes_id = trim($id);

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
			<input type="" value="Volver" />
			</form>
			<?php
		
	

		?>



		

		
	</body>
</html>