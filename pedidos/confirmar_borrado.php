<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar borrado</title>
</head>
<body><?php
    require "../comunes/auxiliar.php";



    if (isset($_get['id'])):
        $id = trim($_get['id']);
        if ($id != ""):
            $con = conectar();
            $res = pg_query($con, "select numero from pedidos where id::text = '$id'");

            if (pg_num_rows($res) == 1):
                $res = 
            endif;
        endif;       
    endif;?>    
</body>
</html>