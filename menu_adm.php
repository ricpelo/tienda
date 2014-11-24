<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administración - Menú principal</title>
</head>
<body><?php
    require 'comunes/auxiliar.php';

    $usuario= comprobar_usuario();
    $nick = comprobar_nick($usuario);?>

    <p style="text-align: right">Administrador: <strong><?=$nick?></strong></p><hr>

    <h1>Menú principal</h1>
    <a href="/tienda/clientes/index.php"><button>Gestión de clientes</button></a>
    <a href="/tienda/articulos/index.php"><button>Gestión de artículos</button></a>
    <a href="/tienda/pedidos/index.php"><button>Gestión de pedidos</button></a>
    
</body>
</html>