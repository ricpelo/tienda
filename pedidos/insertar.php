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
    $sentido  = (isset($_GET['sentido']))  ? trim($_GET['sentido']) : 'asc';

    // Rellena el array de articulos e indexa los $elementos comenzando por $ind_art en un array auxiliar;

        $numero_articulos = (isset($_SESSION['listado_articulos'])) ? count($_SESSION['listado_articulos']) :
                             rellenar_array_articulos($filtro);


        if (isset($_POST['codigo_add'])) {
          $codigo_art = $_POST['codigo_add'];
          $_SESSION['listado_articulos'][$codigo_art][2] --;
          insertar_articulo_pedido($codigo_art);
        }

        if (isset($_POST['codigo_del'])) {
          $codigo_art = $_POST['codigo_del'];
            $_SESSION['listado_articulos'][$codigo_art][2] ++;
            borrar_articulo_pedido($codigo_art);
        }


        $elementos =8;

        // CONTROL DEL PAGINADO

        $ind_art = (isset($_POST['ind_art'])) ? $_POST['ind_art'] : 0;

        if (isset($_POST['pag_atras'])) {
          if ($ind_art-5 >=0 ){
            $ind_art -= $elementos;
          } else {
            $ind_art = 0;
          }
        }

        if (isset($_POST['pag_adelante'])) {
          if ($ind_art+5 <=$numero_articulos ){
            $ind_art += $elementos;
          } else {
            $ind_art = $ind_art;
          }
        }

        $listado_articulos_paginado = (array_slice($_SESSION['listado_articulos'], $ind_art, $elementos, true));?>


  <div class="principal"><?php
    include ('../comunes/header.php');?>

    <section class="contenido"><?php

    // Se ha pulsado finalizar pedido
      if (isset($_POST['pedido_fin'])) {
        $numero = calcular_numero_pedido();?>
          <div class="articulos">
            <table>
              <thead>
                <tr>
                  <th colspan="6" class="titulo2">
                    Pedido Nº: <?= $numero ?> guardado.
                  </th>
                </tr>
                <tr class="subtitulo">
                  <th width="18%">Código</th>
                  <th width="50%">Descripción</th>
                  <th width="11%">Precio</th>
                  <th width="6%">Cant.</th>
                  <th width="12%">Subtotal</th>
                  <th width="7%">Acción</th>

                  </th>
                </tr>
              </thead>
              <tbody><?php
              foreach ($_SESSION['detalle_pedido'] as $k => $v) {?>
                <tr>
                  <td><?= $k ?></td>
                  <td class="izquierda"><?= $v[0] ?></td>
                  <td class="derecha"><?= number_format($v[1], 2, ',', '.') ?> €</td>
                  <td class="derecha"><?= $v[2] ?></td><?php
                  $total_linea = $v[1]*$v[2];?>
                  <td class="derecha"><?= number_format($total_linea, 2, ',', '.') ?> €</td>
                  <td>
                    <form style="display:inline" action="insertar.php" method="POST">
                      <input type="hidden" name="codigo_del" value ="<?= $k ?>" />
                      <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                      <input type="image" src="../images/borrar24.png" title="Borrar" alt="Borrar" />
                    </form>
                  </td>
                </tr><?php
              }?>
              </tbody>
            </table>
          </div>
          <div class="paginador">
            <div class="paginado_centro subtitulo">
              <div class="centro">
                <form action="insertar.php" method="POST">
                  <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
                  <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                  <input type="submit" value="Volver" title="Volver" alt="Volver" />
                </form>
              </div>
            </div>
          </div><?php
        finalizar_pedido($numero);
      } else {

      // Si no ha pulsado finalizar pedido ni ver el carro se muestran los artículos.
        if (!isset($_POST['ver_carro'])) {?>
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
                      <input type="image" src="../images/insertar24.png" title="Añadir" alt="Añadir" />
                    </form>
                    <form style="display:inline" action="insertar.php" method="POST">
                      <input type="hidden" name="codigo_del" value ="<?= $k ?>" />
                      <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
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
              <input type="image" src="../images/left_arrow.png" title="Pág. Anterior" alt="Pág. Anterior" />
            </form>
            <div class="paginado_centro subtitulo">
              <div class="centro">
                <?= $ind_art+1 ?>-<?= (($ind_art+5 > $numero_articulos) ? $numero_articulos : $ind_art+5) ?> de <?= $numero_articulos ?> artículos
              </div>
            </div>
            <form class="paginado_der" action="insertar.php" method="POST">
              <input type="hidden" name="pag_adelante" value ="" />
              <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
              <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
              <input type="image" src="../images/right_arrow.png" title="Pág. Siguiente" alt="Pág. Siguiente" />
            </form>
          </div><?php
        } else { 

          // Se ha pulsado ver carro?>
          <div class="articulos">
            <table>
              <thead>
                <tr>
                  <th colspan="6" class="titulo2">
                    Detalle pedido actual
                  </th>
                </tr>
                <tr class="subtitulo">
                  <th width="18%">Código</th>
                  <th width="50%">Descripción</th>
                  <th width="11%">Precio</th>
                  <th width="6%">Cant.</th>
                  <th width="12%">Subtotal</th>
                  <th width="7%">Acción</th>

                  </th>
                </tr>
              </thead>
              <tbody><?php
              foreach ($_SESSION['detalle_pedido'] as $k => $v) {?>
                <tr>
                  <td><?= $k ?></td>
                  <td class="izquierda"><?= $v[0] ?></td>
                  <td class="derecha"><?= number_format($v[1], 2, ',', '.') ?> €</td>
                  <td class="derecha"><?= $v[2] ?></td><?php
                  $total_linea = $v[1]*$v[2];?>
                  <td class="derecha"><?= number_format($total_linea, 2, ',', '.') ?> €</td>
                  <td>
                    <form style="display:inline" action="insertar.php" method="POST">
                      <input type="hidden" name="codigo_del" value ="<?= $k ?>" />
                      <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                      <input type="image" src="../images/borrar24.png" title="Borrar" alt="Borrar" />
                    </form>
                  </td>
                </tr><?php
              }?>
              </tbody>
            </table>
          </div>
          <div class="paginador">
            <div class="paginado_centro subtitulo">
              <div class="centro">
                <form action="insertar.php" method="POST">
                  <input type="hidden" name="ind_art" value ="<?= $ind_art ?>" />
                  <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
                  <input type="submit" value="Volver" title="Volver" alt="Volver" />
                </form>
              </div>
            </div>
          </div><?php
        }
      }?>

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
          <form class="carro_form" action="insertar.php" method="POST">
            <input type="hidden" name="ver_carro" value ="" />
            <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
            <input type="image" src="../images/finalizar36.png" title="Volver" alt="Volver" />
          </form>          
          <div class="leyenda_carro">
            <?= cuenta_unidades() ?>
          </div>

        </div><?php
          if (count($_SESSION['detalle_pedido']) >0 ) {?> 
            <p><u>TOTAL PEDIDO</u> <br/><?= number_format($_SESSION['total_pedido'], 2, ',', '.') ?> €</p>
            <form style="display:inline" action="insertar.php" method="POST">
              <input type="hidden" name="pedido_fin" value ="" />
              <input type="hidden" name="id_unica" value="<?= $_SESSION['id_unica'] ?>" />
              <input type="submit" value="Finalizar Pedido" title="Finalizar Pedido" alt="Finalizar Pedido" />
            </form><?php
          }?>
          <div class="portes">
            <img src="../images/portes.png" /><br/>
            * Para pedidos superiores a 50€
          </div>
      </div>
      <div class="footer_lateral">
        <p class="copyright">
          &copy; Iris Sioux Tech Shop <?= date('Y') ?>
        </p>
      </div>
    </aside><?php
    include('../comunes/footer.php'); ?>
  </div>
  </body>
</html>