<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Artículos - Consultas</title>
</head>
<body><?php

    require '../comunes/auxiliar.php';

    

    function menu_paginacion()
    {
        global $fpp;
        global $pag;
        global $npags;
        global $columna;
        global $criterio;
        global $orden;
        global $sentido;
        global $nenlaces;
        
        $url = "&columna=$columna&";
        $url .= "criterio=$criterio&";
        $url .= "orden=$orden&";
        $url .= "sentido=$sentido";
        $siguiente = $pag+1;
        $anterior = $pag-1;
        $nenlaceslado = floor($nenlaces/2);

		if ($pag > 1) { ?>
			<a href="<?="index.php?pag=$anterior".$url?>">&lt;</a><?php
		}

        if ($pag - $nenlaceslado <= 0) {
            $i = 1;
            $fin = $nenlaces;
        } elseif ($npags - $pag <= $nenlaceslado) {
            $i = $npags - $nenlaces;
            $fin = $npags;
        } else{
            $i = $pag-$nenlaceslado;
            $fin = $pag+$nenlaceslado;
        }

		for ($i; $i <= $fin; $i++) {            
            if ($i == $pag) { ?>
                <?= $i ?><?php
            } else { ?>
            <a href="index.php?pag=<?= $i.$url ?>"><?= $i ?></a><?php
            }
        }

		if ($pag < $npags) { ?>
			<a href="<?="index.php?pag=$siguiente".$url?>">&gt;</a><?php
		}
    }

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

    $usuario = comprobar_administrador();
    $nick = comprobar_nick($usuario);

    $nfilas = contar_filas('articulos');
    $fpp = 3;
    $npags = ceil($nfilas/$fpp);
    $pag = isset($_GET['pag']) ? trim($_GET['pag']) : 1;
    $nenlaces = 5;

    $columna = isset($_GET['columna']) ? trim($_GET['columna']) : "codigo";
    $criterio = isset($_GET['criterio']) ? trim($_GET['criterio']) : "";
    $orden = isset($_GET['orden']) ? trim($_GET['orden']) : "codigo";
    $sentido = isset($_GET['sentido']) ? trim($_GET['sentido']) : "asc";

    $cols = array('codigo' => 'Código',
                  'descripcion' => 'Descripción',
                  'precio' => 'Precio',
                  'existencias' => 'Existencias');?>

    <p style="text-align: right">Administrador: <strong><?=$nick?></strong></p><hr><?php

    if (!isset($cols[$columna]) || $criterio == "")
    {
        $where = "";
    }
    else
    {
        if ($columna == 'descripcion')
        {
            $where = "where translate(upper($columna),'ÁÉÍÓÚ','AEIOU') like
                            translate(upper('%$criterio%'),'ÁÉÍÓÚ','AEIOU')";
        }
        else
        {
            $where = "where $columna::text = '$criterio'";
        }        
    }

    if (!isset($cols[$orden]))
    {
        $orden = "codigo";
    }
    
    if ($sentido != "asc" && $sentido != "desc")
    {
        $sentido = "asc";
    }

    $con = conectar();

    $res = pg_query($con, "select *
                             from articulos
                           $where
                            order by $orden $sentido
                            limit $fpp
                           offset ($pag-1)*$fpp");?>

    <form action="index.php" method="get">
        <input type="hidden" name="orden" value="<?= $orden ?>" />
        <input type="hidden" name="sentido" value="<?= $sentido ?>" />
        <input type="hidden" name="pag" value="<?= $pag ?>" />
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

    <table border="1" style="margin:auto">
        <thead><?php
            foreach ($cols as $k => $v):?>
                <th><?php
                    $url  = "index.php?columna=$columna&criterio=$criterio&pag=$pag&";
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
            <tr>
            <td colspan="<?=count($cols)+2?>" style="text-align:center;line-height: 30px"><?=menu_paginacion();?></td>
            </tr>
        </tbody>
    </table>

    <hr>

    <a href="insertar.php">
        <input type="button" value="Insertar un nuevo Artículo" />
    </a><?php
    
    pg_close();?>

</body>
</html>
