<?php session_start(); ?>
<!DOCTYPE html> 
<html>
	<head>
		<meta charset="utf-8"/>
		<title>LOGIN</title> 
	</head>
	<body><?php
		
			require '../comunes/auxiliar.php';

			if (isset($_POST['url'])):
				$url = $_POST['url'];
			else:
				$url = "/tienda";
			endif;

			if(isset($_POST['nick'],$_POST['password'])):
				$url = trim($_POST['url']);
				$nick = trim($_POST['nick']);
				$password = trim($_POST['password']);
				$con = conectar();
				$res = pg_query($con, "select id, rol_id
									from usuarios
									where nick = '$nick' and
											password = md5('$password')"); 
		    	if(pg_num_rows($res) > 0):
		     		$fila = pg_fetch_assoc($res, 0);
		     		$_SESSION['usuario'] = $fila['id'];
		     		$_SESSION['rol'] = $fila['rol'];	

		     		if ($fila['rol_id'] == 2) {
						header("Location: ".$url);
					} else {
						header("Locarion: /tienda/menu_adm.php");
					}  
			 	else: ?>
			 		<h3>Error: Contraseña no válida </h3><?php
			 	endif;
			endif; ?> 	
			 
		    <form action ="login.php" method="post">
		    	<input type="hidden" name="url" value="<?=$url?>">
		    	<label>Nombre: </label>
		    	<input type="text" name="nick"><br>
		    	<label>Contraseña:</label>
		    	<input type="password" name="password"><br>
		    	<input type="submit" value ="Entrar">   
		    </form>
	</body>
</html>