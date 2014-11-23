<?php session_start(); ?>
<!DOCTYPE html> 
<html>
	<head>
		<meta charset="utf-8"/>
		<title>LOGIN</title> 
	</head>
	<body><?php
		
			require '../comunes/auxiliar.php';
			
			if(isset($_POST['nick'],$_POST['password'])):
				$nick = trim($_POST['nick']);
				$password = trim($_POST['password']);
				$con = conectar();
				$res = pg_query($con, "select id ,rol_id
									from usuarios
									where nick = '$nick' and
											password = md5('$password')"); 
		    	if(pg_num_rows($res) > 0):
		     		$fila = pg_fetch_assoc($res, 0);

		     		/*$_SESSION['usuario'] = $fila['id'];	
		     		header("Location:".$_SESSION['url']);  
		     		header("Location: /tienda/articulos");  // Este header es para probar que funciona*/

		     		$_SESSION['usuario'] = $fila['id'];
		     		$_SESSION['rol'] = $fila['rol_id'];

		     		if ($_SESSION['rol'] == 2) {
						header("Location: /tienda/pedidos/insertar.php");
					} else {
						header("Location: /tienda/menu_adm.php");
					}
			 	else: ?>
			 		<h3>Error: Contraseña no válida </h3><?php
			 	endif;
			endif; ?> 	
			 
		    <form action ="login.php" method="post">
		    	<label>Nombre: </label>
		    	<input type="text" name="nick"><br>
		    	<label>Contraseña:</label>
		    	<input type="password" name="password"><br>
		    	<input type="submit" value ="Entrar">   
		    </form>
	</body>
</html>