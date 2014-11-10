<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Modificar Artículo</title>
</head>
<body><?php
		require '../comunes/auxiliar.php';

		$id = 1;

		$con = conectar();

		$res = pg_query($con, "select *
                             from articulo
                            where id = '$id'");

		$codigo = (isset($_GET['codigo'])) ? trim($_GET['codigo']) : "";
		$descripcion = (isset($_GET['descripcion'])) ? trim($_GET['descripcion']) : "";;
		$precio = (isset($_GET['precio'])) ? trim($_GET['precio']) : "";;
		$existencias = (isset($_GET['existencias'])) ? trim($_GET['existencias']) : "";;

		?>

		<form action="" method="post">
			<label for="codigo">Código:</label>
      <input type="text" name="codigo" value="<?= $codigo ?>" size="6" /><br/>
      <label for="descripcion">Descripción:</label>
      <input type="text" name="descripcion" value="<?= $descripcion ?>" size="40"/><br/>
      <label for="precio">Precio:</label>
      <input type="text" name="precio" value="<?= $precio ?>" /><br/>
      <label for="existencias">Existencias:</label>
      <input type="text" name="existencias" value="<?= $existencias ?>" /><br/>
			<input type="submit" value="Modificar" />
			
		</form>
</body>
</html>