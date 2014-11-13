<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Altas Clientes</title>
</head>
<body>
	<?php 
	require'../comunes/auxiliar.php';

	//creamos el array que contendá los errores
	$errores = array();
	//definimos las variables por si no lo hemos cojido con $_POST
	$dni=(isset($_POST['dni']) ? trim($_POST['dni']) : "");
	//echo($dni);
	//funcion que comprueba si hay mensages en $errores y lanza una excepcion
	function comprobar_errores(){
      global $errores;
      
      if (!empty($errores))
      {
        throw new Exception();
      }
    }

	//funcion que comprueba si el dni está en la bd
	//comprueba que el dni no sea blanco o mayor de 9
	function comprobar_dni(){
		global $errores;

		if ($_POST['dni']=="") {
			$errores[]="El valor DNI no puede estár vacio";
			comprobar_errores();
		}
	}
	//aqui empieza el programa php
	//comprueba si se han mandado los valores del formulario por post, es decir, si venimos del submit
	if (isset($_POST['nombre']) && isset($_POST['apellidos']) && isset($_POST['dni']) && isset($_POST['codigo']) && isset($_POST['usuario_id'])) {
		$codigo=trim($_POST['codigo']);
		$nombre=trim($_POST['nombre']);
		$apellidos=trim($_POST['apellidos']);
		$dni=trim($_POST['dni']);
		$direccion=trim($_POST['direccion']);
		$poblacion=trim($_POST['poblacion']);
		$codigopostal=trim($_POST['codigopostal']);
		$usuario_id=trim($_POST['usuario_id']);
		try {
			//comprobamos el dni
			comprobar_dni();




			///antes de insertar hay que hacer comprobaciones de los datos que vamos a insertar
			///hacer comprobaciones
			

			//conectamos con la base de datos e insertamos los datos
			$con=conectar();
			$res=pg_query($con,"begin");
			$res=pg_query($con,"lock table clientes in share mode");
			//sentencia sql que inserta un registro en la tabla
			$res=pg_query($con,"insert into clientes 
								(codigo, nombre, apellidos, dni, direccion, poblacion, codigo_postal, usuario_id)
								values ($codigo, '$nombre', '$apellidos', '$dni', '$direccion', '$poblacion', 
									'$codigopostal', $usuario_id)");

			//hay que comprobar si se ha insertado correctamente

			?><p>El cliente se ha insertado correctamente.</p><?php

			//si el flujo del programa llega aqui, es que se ha insertado un cliente y
			//hay que evitar que salga el formulario de alta
			//para ello se usa goto
			goto fin;
		} catch (Exception $e) {
			foreach ($errores as $error) {
				?>
				<p>Error: <?= $error ?></p>
				<?php
			}
		}finally{
			//aqui nos aseguramos que pase lo que pase hacemos commit de la tabla que usabamos y cerramos la conexion si existe
			if (isset($con) && $con!= FALSE) {
				$res=pg_query($con,"commit");
				pg_close($con);
			}
		}
	}
	$con=conectar();
	$res=pg_query($con, "select id, nick from usuarios");
	?>
	


	<h3>Insertar cliente</h3>
	<form action="altas_clientes.php" method="post">
		<label for="codigo">Codigo *:</label>
		<input type="text" name="codigo"><br>
		<label for="nombre">Nombre *: </label>
		<input type="text" name="nombre"><br>
		<label for="apellidos">Apellidos *: </label>
		<input type="text" name="apellidos"><br>
		<label for="dni">DNI *:</label>
		<input type="text" name="dni"><br>
		<label for="direccion">Dirección: </label>
		<input type="text" name="direccion"><br>
		<label for="poblacion">Población: </label>
		<input type="text" name="poblacion"><br>
		<label for="codigopostal">Código postal: </label>
		<input type="text" name="codigopostal"><br>

		<select name="usuario_id" ><?php 
			for($i=0; $i< pg_num_rows($res); $i++){
				$fila=pg_fetch_assoc($res, $i);
				?>
				<option value="<?= $fila['id'] ?>">
					<?= $fila['nick']?>
				</option>
				<?php
			}
			?>
		</select>

		<input type="submit" value="Insertar">

	</form>

<?php
fin:
?>
</body>
</html>