<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="pedidos.css">
    <title>Crear un nuevo pedido</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

  // CREA VARIABLES DE INICIO NECESARIAS

    // Crea una variable de sesión única para impedir la reinserción de datos.
    if (!isset($_SESSION['id_unica'])) {
      $id_unica = md5(uniqid(rand(), true));
      $_SESSION['id_unica'] = $id_unica;
    } else {
      // echo $_SESSION['id_unica'];
    }

    // Recibe el usuario o redirige a login.
    if (isset($_SESSION['usuario'])) {
      $user_id = $_SESSION['usuario'];
      } else {
      $_SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: ../usuarios/login.php");
    }

    // Crea el array detalle_pedido si aún no existe
    if (!isset($_SESSION['detalle_pedido'])) {
      $_SESSION['detalle_pedido'] = array();
    }

    // Crea la variable total_pedido si aún no existe.
    if (!isset($_SESSION['total_pedido'])) {
      $_SESSION['total_pedido'] = 0;
    }

  // COMPRUEBA DATOS RECIBIDOS POST

    $filtro = (isset($_POST['filtro'])) ? trim($_POST['filtro']) : '';

    if (isset($_POST['codigo_add'])) {
      $codigo_art = $_POST['codigo_add'];
      insertar_articulo_pedido($codigo_art);
    }

    if (isset($_POST['codigo_del'])) {
      $codigo_art = $_POST['codigo_del'];
      borrar_articulo_pedido($codigo_art);
    }

    if (isset($_POST['pedido_fin'])) {
      $numero = calcular_numero_pedido();
      finalizar_pedido($numero);
    }

    $con = conectar();
    $res = pg_query($con, "select * from articulos where upper(descripcion) like upper('%$filtro%')");?>

  <div class="principal"><?php
    include ('../comunes/header.php');?>

    <section class="contenido">
      <table>
        <thead>
          <tr>
            <th colspan="4"  class="titulo2">
              LISTADO ARTICULOS
            </th>
          </tr>
          <tr class="subtitulo">
            <th width="18%">Código</th>
            <th width="57%">Descripción</th>
            <th width="12%">Precio</th>
            <th width="13%">Acciones</th>
          </tr>
        </thead>
        <tbody><?php
        for ($i = 0; $i < pg_num_rows($res); $i++) {
          $fila = pg_fetch_assoc($res, $i);
          extract($fila);?>
          <tr>
            <td><?= $codigo ?></td>
            <td class="izquierda"><?= $descripcion ?></td>
            <td class="derecha"><?= $precio ?></td>
            <td>
              <form style="display:inline" action="insertar.php" method="POST">
                <input type="hidden" name="codigo_add" value ="<?= $codigo ?>" />
                <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                <input type="image" src="../images/insertar24.png" title="Añadir" alt="Añadir" />
              </form>
              <form style="display:inline" action="insertar.php" method="POST">
                <input type="hidden" name="codigo_del" value ="<?= $codigo ?>" />
                <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                <input type="image" src="../images/borrar24.png" title="Borrar" alt="Borrar" />
              </form>
            </td>
          </tr><?php
        }?>
        </tbody>
      </table>
      <p></p>
      <table>
        <thead>
          <tr>
            <th colspan="5" class="titulo2">
              DETALLE PEDIDO
            </th>
          </tr>
          <tr>
            <th width="18%">Código</th>
            <th width="55%">Descripción</th>
            <th width="12%">Precio</th>
            <th width="5%">Cant.</th>
            <th width="10%">Subtotal</th>
          </tr>
        </thead>
        <tbody><?php
        foreach ($_SESSION['detalle_pedido'] as $k => $v) {?>
          <tr>
            <td><?= $k ?></td>
            <td class="izquierda"><?= $v[0] ?></td>
            <td class="derecha"><?= $v[1] ?></td>
            <td class="derecha"><?= $v[2] ?></td><?php
            $total_linea = $v[1]*$v[2];?>
            <td class="derecha"><?= $total_linea ?></td> 
          </tr><?php
        }?>
        </tbody>
      </table>
    </section>
    <aside class="bloque_derecho">
      <div class="contenido_lateral">
        <h3 class="icono_encabezado titulo">
          Filtro artículos
        </h3>
        <form action="insertar.php" method="POST">
          <input class="filtrar" type="text" name="filtro" placeholder="Búsqueda para filtro" />
          <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
        </form>
        <h3 class="icono_encabezado titulo">
          Resumen Pedido
        </h3>
        <div class="carro">
          <p class="leyenda_carro">
            <?= cuenta_unidades() ?>
          </p>
        </div>
          <p>TOTAL PEDIDO <br/><?= $_SESSION['total_pedido'] ?> €</p><?php
          if (count($_SESSION['detalle_pedido']) >0 ) {?>
            <form style="display:inline" action="insertar.php" method="POST">
              <input type="hidden" name="pedido_fin" value ="" />
              <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
              <input type="submit" value="Finalizar Pedido" title="Finalizar Pedido" alt="Finalizar Pedido" />
            </form><?php
          }?>
          <p class="copyright">
            Iris Sioux Tech Shop <?= date('Y') ?>
          </p>
      </div>
    </aside><?php

    include('../comunes/footer.php'); ?>
  </div>
  </body>
</html>