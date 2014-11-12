<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Altas Clientes</title>
</head>
<body>
	<?php 
	require'../comunes/auxiliar.php';
	//aqui empieza el programa php
	//comprueba si se han mandado los valores del formulario por post, es decir, si venimos del submit
	if (isset($_POST['nick']), isset($_POST['password'])) {
		$nick=trim($_POST['nick']);
		$password=trim($_post['password']);

		try {
			//antes de insertar hay que hacer comprobaciones de los datos que vamos a insertar
			//hacer comprobaciones
			

			//conectamos con la base de datos e insertamos los datos
			$con=conectar();
			$res=pg_query($con,"begin");
			$res=pg_query($con,"lock table clientes in share mode");
			//sentencia sql que inserta un registro en la tabla
			$res=pg_query($con,"insert into clientes (nick, password)
								values ($nick, $password)");

			//hay que comprobar si se ha insertado correctamente

			?><p>El cliente se ha insertado correctamente.</p><?php

			//si el flujo del programa llega aqui, es que se ha insertado un cliente y
			//hay que evitar que salga el formulario de alta
			//para ello se usa goto
			goto fin;
		} catch (Exception $e) {
			//aqui hay que mostrar los herrores del array que hay que crear
		}finally{
			//aqui nos aseguramos que pase lo que pase hacemos commit de la tabla que usabamos y cerramos la conexion si existe
			if (isset($con) && $con!= FALSE) {
				$res=pg_query($con,"commit");
				pg_close($con);
			}
		}
	}
	?>
	


	<h3>Insertar cliente</h3>
	<form action="altas_clientes.php" method="post">
		<label for="nick">Nick *: </label>
		<input type="text" maxlength="15" name="nick">
		<label for="passw">Contraseña *: </label>
		<input type="password" maxlength="32" name="password">
		<input type="submit" value="Insertar">
	</form>

<?php
fin:
?>
</body>
</html>