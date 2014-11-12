<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>tienda online</title>
</head>
<body><?php

  require '../comunes/auxiliar.php';

  $con = conectar();
  $id = 1;
  $cols= array('cliente_id' => 'Id del cliente',
               'importe' => 'Importe del pedido', 
               'gastos_envio' => 'Gastos de envio');

  if(isset($_GET['columna'], $_GET['criterio']))
  {
    $columna= $_GET['columna'];
    $criterio= $_GET['criterio'];
  }else
  {
    $columna= "cliente_id";
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


  $res= pg_query($con, "select * from pedidos $where"); ?>

  <form action="index.php" method="GET">
      
      <label for="columna">Buscar por: </label>

      <select name="columna">
      <?php foreach ($cols as $k => $v) { ?>
               <option value="<?= $k ?>">
                 <?= $v ?>
               </option><?php
            
      } ?> 
      </select>
      <input type="text" name="criterio">
      <input type="submit" value="buscar">
    </form>
  
    <table border="1">
        <thead><?php
            foreach ($cols as $k => $v):?>
                <th><?= $v ?></th><?php
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
                        <form action="confirmar_borrado.php" method="get">
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

    <a href="insertar.php">
        <input type="button" value="Insertar un nuevo Artículo" />
    </a><?php

    ?>
</body>
</html>