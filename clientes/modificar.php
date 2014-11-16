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

		/* FUNCIONES */
		
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
		
		function comprobar_cadena_vacia($codigo, $nombre, $apellidos, $dni, $codigo_postal)
		{
			global $errores;
			$codigo == "" ? $errores[] = "El codigo del cliente esta vacio" : "";
			$nombre == "" ? $errores[] = "El nombre del cliente esta vacio" : "";
			$apellidos == "" ? $errores[] = "Los apellidos del cliente estan vacio" : "";
			$dni == "" ? $errores[] = "El dni del cliente esta vacio" : "";
			$codigo_postal == "" ? $errores[] = "El codigo postal esta vacio" : "";

				
		}

		function validar_codigo($codigo)
		{
			global $errores;

			if(is_numeric($codigo) && $codigo < 999999 && $codigo > -999999)
			{
				$codigo = (int) $codigo;
				return;
			}
			if($codigo == "")
			{
				return;
			}

			else
			{
				$errores[] = "El código no es valido";
			}
		}

	

		function validar_nombre($nombre)
		{
			global $errores;
			if (strlen($nombre) < 16 || $nombre == "")
			{
				return;
			}
			else
			{
				$errores[] = "El nombre no es válido(máximo 15 caracteres)";
			}
				
		}

		function validar_apellidos($apellidos)
		{
			global $errores;
			if (strlen($apellidos) < 31 || $apellidos == "")
			{
				return;
			}
			else
			{
				$errores[] = "Los apellidos no son válidos(máximo 30 caracteres)";
			}
		}

		function validar_dni($dni)
		{
			global $errores;
			if (strlen($dni) == 9 || $dni == "")
			{
				return;
			}
			else
			{
				$errores[] = "El dni no es válido(son 9 caracteres)";
			}
		}

		function validar_direccion($direccion)
		{
			global $errores;
			if (strlen($direccion) < 41 || $direccion == "")
			{
				return;
			}
			else
			{
				$errores[] = "La direccion no es válida(máximo 40 caracteres)";
			}
		}


		function validar_poblacion($poblacion)
		{
			global $errores;
			if (strlen($poblacion) < 41 || $poblacion == "")
			{
				return;
			}
			else
			{
				$errores[] = "La poblacion no es válida(máximo 40 caracteres)";
			}
		}


		function validar_codigo_postal($codigo_postal)
		{
			global $errores;

			if (strlen($codigo_postal) == 5 || $codigo_postal == "")
			{
				return;
			}
			else
			{
				$errores[] = "El código postal no es válido(son 5 números)";
			}
		}


		function comprobar_codigo_existe($codigo, $id)
		{
			global $errores;
			global $con;
			// esta comprobacion es para que deje actualizar los datos del mismo codigo 
			$con = conectar();
			$res = pg_query($con, "select * from clientes 
																				where codigo ::text = '$codigo'
																				and id ::text = '$id'");
			if (pg_num_rows($res) > 0)
				{
					return;
				}

				// por el contrario si queremos modicicar el codigo, hacemos un comprobacion de si existe ya el codigo
				$con = conectar();
				$res = pg_query($con, "select * from clientes 
																				where codigo ::text = '$codigo'");

				if (pg_num_rows($res) > 0)
				{
					$errores[] = "Código repetido, introduce otro código";
				}
			
		}

		function comprobar_dni_existe($dni, $id)
		{

			global $errores;
			global $con;
			//$dni = strtoupper($dni);
			$con = conectar();
			$res = pg_query($con, "select * from clientes 
																				where dni ::text = '$dni'
																				and id ::text = '$id'");
			if (pg_num_rows($res) > 0)
				{
					return;
				}


				$con = conectar();
				$res = pg_query($con, "select * from clientes 
																				where dni ::text = '$dni'");

				if (pg_num_rows($res) > 0)
				{
					$errores[] = "Dni repetido, introduce otro dni";
				}



		}








	/* ------------------------------------------- */






	



	







		if(isset($_POST['codigo'], $_POST['nombre'], $_POST['apellidos'], $_POST['dni']))
		{
			$codigo = trim($_POST['codigo']);
			$nombre = trim($_POST['nombre']);
			$apellidos = trim($_POST['apellidos']);
			$dni = strtoupper(trim($_POST['dni']));
			$direccion = trim($_POST['direccion']);
			$poblacion = trim($_POST['poblacion']);
			$codigo_postal = trim($_POST['codigo_postal']);
			$id = trim($_POST['id']);
			//$codigo_anterior = trim($_POST['codigo_anterior']);
			$dni_anterior = trim($_POST['dni_anterior']);

			/* HACER COMPROBACIONES*/

			try {
    
        
        comprobar_cadena_vacia($codigo, $nombre, $apellidos, $dni, $codigo_postal);
        validar_codigo($codigo);
        validar_nombre($nombre);
        validar_apellidos($apellidos);
        validar_dni($dni);
        validar_direccion($direccion);
        validar_poblacion($poblacion);
        validar_codigo_postal($codigo_postal);

        comprobar_codigo_existe($codigo, $id);
        comprobar_dni_existe($dni, $id);


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
			<input type="text" maxlength="6" name="codigo" value="<?= $codigo ?>" /><br><br>
			<label for="nombre">Nombre *: </label>
			<input type="text" maxlength="15" name="nombre" value="<?= $nombre ?>" /><br><br>
			<label for="apellidos">Apellidos *: </label>
			<input type="text" maxlength="30" name="apellidos" value="<?= $apellidos ?>" /><br><br>
			<label for="dni">DNI *:</label>
			<input type="text" name="dni" value="<?= $dni ?>" /><br><br>
			<label for="direccion">Direccion:</label>
			<input type="text" maxlength="" name="direccion" value="<?= $direccion ?>" /><br><br>
			<label for="poblacion">Poblacion:</label>
			<input type="text" maxlength="" name="poblacion" value="<?= $poblacion ?>" /><br><br>
			<label for="codigo_postal">Código Postal:</label>
			<input type="text" maxlength="" name="codigo_postal" value="<?= $codigo_postal ?>" /><br><br>
			<input type="hidden" name="id" value="<?= $id ?>" />
			<input type="hidden" name="dni_anterior" value="<?= $dni ?>" />
			<!--<input type="hidden" name="codigo_anterior" value="<?= $codigo ?>" /> -->
			<input type="submit" value="Modificar" />
			<a href="index.php"><input type="button" value="Volver" /></a>
			</form>
			<?php
		
			//pg_close($con);
			fin:

		?>



		

		
	</body>
</html>