<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>tienda online</title>
</head>
<body><?php

  require '../comunes/auxiliar.php';

  $con = conectar();
    
    function alta_pedido($cliente_id, $importe, $gastos_envio)
    { 
      global $con;?>
      <td align="center">
        <form action="index.php" method="post">
          <input type="hidden" name="cliente" value="<?= $cliente_id ?>" />
          <input type="hidden" name="importe" value="<?= $importe ?>" />
          <input type="hidden" name="gastos_envio" value="<?= $gastos_envio ?>" />
          <input type="submit" value="Enviar" />
        </form>
      </td><?php
    }
    
    function hacer_pedido($cliente_id, $importe, $gastos_envio)
    {
      global $con;
      
      $pedidos = pg_query($con, "insert into pedidos (cliente_id, importe, gastos_envio)
                             values ($cliente_id, $importe, $gastos_envio)");
    }
    
   function anular_pedido($id)
    {
      global $con;
      
      $pedidos = pg_query($con, "delete from pedidos
                             where id::text = '$id'");
    }?>
</body>
</html>