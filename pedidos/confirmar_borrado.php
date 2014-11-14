<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar borrado</title>
</head>
<body><?php
    require "../comunes/auxiliar.php";

    $con = conectar();

    if (isset($_POST['id'])):
        $id = trim($_POST['id']);

        $res = pg_query($con, "delete from pedidos where id::text = '$id'");

        if (pg_affected_rows($res) > 0):?>
            <p>El pedido se ha eliminado correctamente</p><?php
        else:?>
            <p>No se ha podido eliminar el pedido</p><?php
        endif;?>

        <a href="index.php"><input type="button" value="Volver"></a><?php
    endif;

    if (isset($_GET['id'])):
        $id = trim($_GET['id']);
        if ($id != ""):
            $res = pg_query($con, "select numero from pedidos where id::text = '$id'");

            if (pg_num_rows($res) == 1):
                $fila = pg_fetch_assoc($res,0);
                $numero = $fila['numero'];?>

                <p>¿Desea eliminar el pedido <?=$numero?>?</p>
                <form action="confirmar_borrado.php" method="post">
                    <input type="hidden" name="id" value="<?=$id?>">
                    <input type="submit" value="Aceptar">
                    <a href="index.php"><input type="button" value="Cancelar"></a>
                </form><?php

            endif;
        endif;       
    endif;

    pg_close();
    ?>

</body>
</html>