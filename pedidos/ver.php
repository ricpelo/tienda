<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>tienda online ver</title>
</head>
<body><?php

  require '../comunes/auxiliar.php';

 

  $con = conectar();
  $id = 1;
  $colslinea= array('codigo' => 'Codigo',  
                	'descripcion' => 'Descripcion',
                	'precio' =>  'Precio',
  					'cantidad' => "Cantida");

  $id = $_GET['id'];
     
  $lineaspedidos= pg_query($con, "select * from lineas_pedidos where pedido_id::text = '$id'");?>
  <table border="1">
        <thead><?php
            foreach ($colslinea as $k => $v):?>
                <th><?= $v ?></th><?php
            endforeach;?>
            <!--<th colspan="3">Operaciones</th> -->
        </thead> 
        <tbody><?php
            for ($i = 0; $i < pg_num_rows($lineaspedidos); $i++):
                $fila = pg_fetch_assoc($lineaspedidos, $i);?>
                <tr><?php
                    foreach ($colslinea as $k => $v):?>
                        <td><?= $fila[$k] ?></td><?php
                    endforeach;?>

                    </tr><?php
            endfor;?>
        </tbody>
    </table>
  
</body>
</html>