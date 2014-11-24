<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <title>Index Clientes</title>
	</head>
	<body>
		<?php
			require '../comunes/auxiliar.php';

			$con=conectar();
			//$res=pg_query($con, 'select * from clientes');
			$res=pg_query($con,'select clientes.*, usuarios.nick
								from clientes join usuarios on 
								clientes.usuario_id=usuarios.id');
			$cols=array_keys(pg_fetch_assoc($res, 0));

			?>
			<table border="1">
				<caption>CLIENTES</caption>
				<thead>
					<tr>
					<?php
					foreach ($cols as $col) {
						?><td><?= $col ?></td><?php
					}
					?>
					</tr>
				</thead>
				<tbody>
					<?php
					for ($i=0; $i < pg_num_rows($res); $i++) { 
						$fila=pg_fetch_assoc($res ,$i);
						?>
						<tr>
							<?php
							foreach ($cols as $col) {
								?>
								<td><?= $fila[$col]?></td>
								<?php
							}
							?>
							 <td>
	                        <form action="modificar.php" method="get">
	                            <input type="hidden" name="id" 
	                                  value="<?=$fila['id']?>">
	                            <input type="submit" value="Modificar">
	                        </form>
	                    </td>
	                    <td>
	                        <form action="bajas.php" method="get">
	                            <input type="hidden" name="id" 
	                                  value="<?=$fila['id']?>">
	                            <input type="submit" value="Borrar">
	                        </form>
	                    </td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<?php
			pg_close($con);

			//comienzo de la tabla usuarios
			$con=conectar();
			$res=pg_query($con, 'select * from usuarios');
			$cols=array_keys(pg_fetch_assoc($res, 0));

			?>
			<table border="1">
				<caption>USUARIOS</caption>
				<thead>
					<tr>
					<?php
					foreach ($cols as $col) {
						?><td><?= $col ?></td><?php
					}
					?>
					</tr>
				</thead>
				<tbody>
					<?php
					for ($i=0; $i < pg_num_rows($res); $i++) { 
						$fila=pg_fetch_assoc($res ,$i);
						?>
						<tr>
							<?php
							foreach ($cols as $col) {
								?>
								<td><?= $fila[$col]?></td>
								<?php
							}
							?>
							 <td>
	                        <form action="altas_clientes.php" method="get">
	                            <input type="hidden" name="id" 
	                                  value="<?=$fila['id']?>">
	                            <input type="submit" value="Dar de alta como Cliente">
	                        </form>
	                    </td>
	                    
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		


	<?php

		/*require '../comunes/auxiliar.php';

		function sentido($orden, $sentido, $k)
	    {
	        if ($orden == $k)
	        {
	            $ret = ($sentido == "asc") ? "desc" : "asc";
	        }
	        else
	        {
	            $ret = "asc";
	        }
	      
	        return $ret;
	    }

	    $columna = isset($_GET['columna']) ? $_GET['columna'] : "codigo";
	    $criterio = isset($_GET['criterio']) ? $_GET['criterio'] : "";
	    $orden = isset($_GET['orden']) ? $_GET['orden'] : "codigo";
	    $sentido = isset($_GET['sentido']) ? $_GET['sentido'] : "asc";

	    $cols = array('id' => 'Id',
	    	          'codigo' => 'Código',
	    	          'nombre' => 'Nombre',
	    	          'apellidos' => 'Apellidos',
	    	          'dni' => 'dni',
	    	          'direccion' => 'Dirección',
	    	          'poblacion' => 'Población',
	    	          'codigo_postal' => 'Código postal',
	    	          'usuario_id' => 'Id usuarios');

	     if(isset($_GET['columna'], $_GET['criterio']))
	      {
	        $columna= $_GET['columna'];
	        $criterio= $_GET['criterio'];
	      }
	      else
	      {
	        $columna= "usuario_id";
	        $criterio= "";
	      }

	    if (!isset($cols[$columna]) || $criterio == "")
	    {
	        $where = "";
	    }
	    else
	    {
	    	$where = "where $columna::text = '$criterio'";
	    }

	    if (!isset($cols[$orden]))
	    {
	        $orden = "usuario_id";
	    }
	    
	    if ($sentido != "asc" && $sentido != "desc")
	    {
	        $sentido = "asc";
	    }

	    $con = conectar();

	    $res = pg_query($con, "select *
	                             from clientes
	                           $where
	                            order by $orden $sentido");?>

	    <form action="index.php" method="get">
	        <input type="hidden" name="orden" value="<?= $orden ?>" />
	        <input type="hidden" name="sentido" value="<?= $sentido ?>" />
	        <label for="columna">Buscar por:</label>
	        <select name="columna"><?php
	            foreach ($cols as $k => $v):?>
	                <option value="<?=$k?>" <?= $columna == $k ? "selected" : ""?>>
	                    <?=$v?>
	                </option><?php
	            endforeach;?>
	        </select>
	        <input type="text" name="criterio" value="<?=$criterio?>">
	        <input type="submit" value="Buscar">
	    </form>
	    <hr>

	    <table border="1">
	        <thead><?php
	            foreach ($cols as $k => $v):?>
	                <th><?php
	                    $url  = "index.php?columna=$columna&criterio=$criterio&";
	                    $url .= "orden=$k&sentido=";
	                    $url .= sentido($orden, $sentido, $k); ?>
	                    <a href="<?= $url ?>"><?= $v ?></a><?php
	                    if ($orden == $k): ?>
	                      <?= ($sentido == "asc") ? "↓" : "↑" ?><?php
	                    endif; ?>
	                </th><?php
	            endforeach;?>
	            <th colspan="2">Operaciones</th>
	        </thead> 
	        <tbody><?php
	            for ($i = 0; $i < pg_num_rows($res); $i++):
	                $fila = pg_fetch_assoc($res, $i);?>
	                <tr><?php
	                    foreach ($cols as $k => $v):?>
	                        <td><?= $fila[$k] ?></td><?php
	                    endforeach;?>
	                    <td>
	                        <form action="modificar.php" method="get">
	                            <input type="hidden" name="id" 
	                                  value="<?=$fila['id']?>">
	                            <input type="submit" value="Modificar">
	                        </form>
	                    </td>
	                    <td>
	                        <form action="bajas.php" method="get">
	                            <input type="hidden" name="id" 
	                                  value="<?=$fila['id']?>">
	                            <input type="submit" value="Borrar">
	                        </form>
	                    </td>
	                </tr><?php
	            endfor;?>
	        </tbody>
	    </table>

	    <hr>

	    <a href="altas_clientes.php">
	        <input type="button" value="Insertar un nuevo Cliente" />
	    </a><?php
	    
	    pg_close();*/
	?>
	</body>
</html>