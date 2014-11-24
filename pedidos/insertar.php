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
    require 'auxiliar.php';

  // CREA VARIABLES DE INICIO NECESARIAS

    define ('ELE_PAG', 8);
    // Crea una variable de sesión única para impedir la reinserción de datos.
    if (!isset($_SESSION['id_unica'])) {
      $id_unica = md5(uniqid(rand(), true));
      $_SESSION['id_unica'] = $id_unica;
    }

    // Recibe el usuario o redirige a login.
//      $user_id = comprobar_usuario();
//      $nick = comprobar_nick($user_id);

    // Crea el array detalle_pedido si aún no existe
    if (!isset($_SESSION['detalle_pedido'])) {
      $_SESSION['detalle_pedido'] = array();
    }

    // Crea la variable total_pedido si aún no existe.
    if (!isset($_SESSION['total_pedido'])) {
      $_SESSION['total_pedido'] = 0;
    }

    // Crea la variable stock--> Muestra o no los artículos sin stock.
    if (isset($_SESSION['stock'])) {
      if (isset($_POST['stock_flag'])) {
        $_SESSION['stock'] = ($_SESSION['stock']) ? false : true;
      }
      if ($_SESSION['stock']) {
        $img_stock = "checked.png";
        $img_stock_over = "checked_over.png";
      } else {
        $img_stock = "check.png";
        $img_stock_over = "check_over.png";
      }
    } else {
      $_SESSION['stock'] = true;
      $img_stock = "checked.png";
      $img_stock_over = "checked_over.png";
    }

  // COMPRUEBA DATOS RECIBIDOS POST

    $filtro = (isset($_POST['filtro'])) ? trim($_POST['filtro']) : '';
    $sentido  = (isset($_GET['sentido']))  ? trim($_GET['sentido']) : 'asc';
    

    // Rellena el array de articulos e indexa los ELE_PAG comenzando por $ind_art en un array auxiliar;

        if (isset($_POST['vaciar_carro'])) {
          vaciar_carro();
        }

        rellenar_array_articulos($filtro, "descripcion", $_SESSION['stock']); // El segundo parámetro  define el orden.
        $numero_articulos = total_articulos();

        if (isset($_POST['codigo_add'])) {
          $codigo_art = $_POST['codigo_add'];
          insertar_articulo_pedido($codigo_art);
        }

        if (isset($_POST['codigo_del'])) {
          $codigo_art = $_POST['codigo_del'];
          borrar_articulo_pedido($codigo_art);
        }

        // CONTROL DEL PAGINADO

        $ind_art = (isset($_POST['ind_art'])) ? $_POST['ind_art'] : 0;

        if (isset($_POST['pag_atras'])) {
          if ($ind_art-ELE_PAG >=0 ){
            $ind_art -= ELE_PAG;
          } else {
            $ind_art = 0;
          }
        }

        if (isset($_POST['pag_adelante'])) {
          if ($ind_art+ELE_PAG <=$numero_articulos ){
            $ind_art += ELE_PAG;
          } else {
            $ind_art = $ind_art;
          }
        }

        $listado_articulos_paginado = (array_slice($_SESSION['listado_articulos'], $ind_art, ELE_PAG, true));?>


  <div class="principal"><?php
    include ('../comunes/header.php');?>

    <section class="contenido">
          <div class="articulos">
            <table>
              <thead>
                <tr>
                  <th colspan="5"  class="titulo2">
                    LISTADO ARTICULOS
                  </th>
                </tr>
                <tr class="subtitulo">
                  <th width="18%">Código</th>
                  <th width="50%">Descripción</th>
                  <th width="11%">Precio</th>
                  <th width="6%">Stock</th>
                  <th width="14%">Acciones</th>
                </tr>
              </thead>
              <tbody><?php

              foreach ($listado_articulos_paginado as $k => $v) {?>
                <tr>
                  <td><?= $k ?></td>
                  <td class="izquierda"><?= $v[0] ?></td>
                  <td class="derecha"><?= number_format($v[1], 2, ',', '.') ?> €</td>
                  <td class="derecha"><?= $v[2] ?></td>
                  <td>
                    <form style="display:inline" action="insertar.php" method="POST">
                      <input type="hidden" name="codigo_add" value ="<?= $k ?>" />
                      <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                      <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
                      <input type="hidden" name="filtro" value="<?= $filtro ?>" />
                      <input type="image" src="../images/insertar24.png" title="Añadir" alt="Añadir" />
                    </form>
                    <form style="display:inline" action="insertar.php" method="POST">
                      <input type="hidden" name="codigo_del" value ="<?= $k ?>" />
                      <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                      <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
                      <input type="hidden" name="filtro" value="<?= $filtro ?>" />
                      <input type="image" src="../images/borrar24.png" title="Borrar" alt="Borrar" />
                    </form>
                  </td>
                </tr><?php
              }?>
              </tbody>
            </table>
          </div>
          <div class="paginador">
            <form class="paginado_izq" action="insertar.php" method="POST">
              <input type="hidden" name="pag_atras" value ="" />
              <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
              <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
              <input type="hidden" name="filtro" value="<?= $filtro ?>" />
              <input type="image" src="../images/left_arrow.png" title="Pág. Anterior" alt="Pág. Anterior" />
            </form>
            <div class="paginado_centro subtitulo">
              <div class="centro">
                <?= $ind_art+1 ?>-<?= (($ind_art+ELE_PAG > $numero_articulos) ? $numero_articulos : $ind_art+ELE_PAG) ?> de <?= $numero_articulos ?> artículos
              </div>
            </div>
            <form class="paginado_der" action="insertar.php" method="POST">
              <input type="hidden" name="pag_adelante" value ="" />
              <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
              <input type="hidden" name="filtro" value="<?= $filtro ?>" />
              <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
              <input type="image" src="../images/right_arrow.png" title="Pág. Siguiente" alt="Pág. Siguiente" />
            </form>
          </div>

    </section>
    <aside class="bloque_derecho">
      <div class="contenido_lateral">
        <h3 class="icono_encabezado titulo2">
          Filtro artículos
        </h3>
        <form action="insertar.php" method="POST">
          <input class="filtrar" type="text" name="filtro" placeholder="Búsqueda para filtro" />
          <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
        </form><?php
 ?>
        <form class="stock" action="insertar.php" method="POST">
          <input type="hidden" name="stock_flag" value ="<?= $stock_flag ?>" />
          <input type="hidden" name="filtro" value="<?= $filtro ?>" />
          <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
          <span class="leye_stock">Sólo Artículos en stock</span>
          <input class="stock" type="image" src="../images/<?= $img_stock ?>" title="Sólo Stock" alt="Sólo Stock" />
          
        </form>
        <br/>        
        <h3 class="icono_encabezado titulo2">
          Resumen Pedido
        </h3><?php
        if (cuenta_unidades() >0 ) {
          $carro = "../images/finalizar36.png";
        } else {
          $carro = "../images/finalizar_vacia.png";
        }?>
        <div class="carro">
          <form class="carro_form" action="vercarro.php" method="POST">
            <input type="hidden" name="ver_carro" value ="" />
            <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
            <input type="image" src="<?= $carro ?>" title="Ver Cesta" alt="Ver Cesta" />
          </form>          
          <div class="leyenda_carro">
            <?= cuenta_unidades() ?>
          </div>

        </div><?php
          if (count($_SESSION['detalle_pedido']) >0 ) {?> 
            <p><u>TOTAL PEDIDO</u> <br/><?= number_format($_SESSION['total_pedido'], 2, ',', '.') ?> €</p>
            <div class="carro">
              <form class="carro_form" action="vercarro.php" method="POST">
                <input type="hidden" name="vaciar_carro" value ="" />
                <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                <input type="image" src="../images/vaciar_carro.png" title="Vaciar Cesta" alt="Vaciar Cesta" />
              </form>
            </div>
            <div class="portes">
              <img class="img_portes" src="../images/portes.png" /><br/>
              * Para pedidos superiores a 50€
            </div><?php
          }?>

      </div>
        <p class="copyright">
          &copy; Iris Sioux Tech Shop <?= date('Y') ?>
        </p>
    </aside><?php
    include('../comunes/footer.php'); ?>
  </div>
  </body>
</html>