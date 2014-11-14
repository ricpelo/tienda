<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

    // Recibe el usuario o redirige a login.
    if (isset($_SESSION['usuario'])) {
      $user_id = $_SESSION['usuario'];
      var_dump($user_id);
    } else {
      $_SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: ../usuarios/login.php");
    }

    if (!isset($_SESSION['pedido_id'])) {
      $numero = calcular_numero_pedido();
      $pedido_id = crear_pedido($numero);
      $_SESSION['pedido_id'] = $pedido_id;
    } else {
      $pedido_id = $_SESSION['pedido_id'];
    }

    function crear_pedido($numero) {

      $usuario_id = $_SESSION['usuario'];
      $con = conectar();
      $res = pg_query($con, "select * from clientes where usuario_id::text = '$usuario_id'");
      echo pg_affected_rows($res);
      if (pg_affected_rows($res) == 1) {
        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
        $id = (int)($id);
        $codigo = (int)($codigo);

        $res = pg_query($con, "insert into pedidos (numero, fecha, cliente_id, codigo, nombre, 
                                                    apellidos, dni, direccion, poblacion, codigo_postal,
                                                    importe, gastos_envio)
                                           values  ($numero, current_date, $id, $codigo,'$nombre',
                                                    '$apellidos', '$dni', '$direccion', '$poblacion',
                                                    '$codigo_postal', 0,0)");
        $res = pg_query($con, "select id from pedidos where numero = $numero");
        if (pg_affected_rows($res) == 1) { /* Devuelve el id correspondiente al número
                                              de pedido creado */
          $fila = pg_fetch_assoc($res, 0);
          extract($fila);
          return $id;
        } else { // Si no existe ningún pedido con ese número (no se ha creado) devuelve 0
          return 0;
        }
      }
    }

    function calcular_numero_pedido() {
      $con = conectar();
      $res = pg_query($con, "select max(numero) as numero from pedidos");
      if (pg_affected_rows($res) == 1) {
        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
        $numero ++;
        return $numero;
      }
    }

    function insertar_articulo_pedido($pedido_id, $codigo_art) {

      $pedido_id = (int) ($pedido_id);
      $codigo_art = (float)($codigo_art);

      $con = conectar();
      $res = pg_query($con, "select descripcion, precio from articulos where codigo = $codigo_art");
      if (pg_affected_rows($res) == 1) {

        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
        $res = pg_query($con, "insert into lineas_pedidos (pedido_id, cantidad, codigo, descripcion, precio)
                                           values ($pedido_id, 1, $codigo_art, '$descripcion', $precio)");
        var_dump($fila);
      }
    }



    if (isset($_POST['codigo_add'])) {
      $pedido_id = $_POST['pedido_id'];
      $codigo_art = $_POST['codigo_add'];
      insertar_articulo_pedido($pedido_id, $codigo_art);
    }

    if (isset($_POST['codigo_del'])) {
      echo "Se ha borrado un artículo";
    }

    $con = conectar();
    $res = pg_query($con, "select * from articulos");?>

    <table>
      <thead>
        <tr colspan="5">
          <th>
          <p>LISTADO ARTICULOS</p>
          </th>
        </tr>
        <tr>
          <th>Código</th>
          <th>Descripción</th>
          <th>Precio</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody><?php
      for ($i = 0; $i < pg_num_rows($res); $i++) {
        $fila = pg_fetch_assoc($res, $i);
        extract($fila);?>
        <tr>
          <td><?= $codigo ?></td>
          <td><?= $descripcion ?></td>
          <td><?= $precio ?></td>
          <td>
            <form style="display:inline" action="insertar.php" method="POST">
              <input type="hidden" name="codigo_add" value ="<?= $codigo ?>" />
              <input type="image" src="../images/insertar24.png" title="Añadir" alt="Añadir" />
            </form>
            <form style="display:inline" action="insertar.php" method="POST">
              <input type="hidden" name="codigo_del" value ="<?= $codigo ?>" />
              <input type="image" src="../images/borrar24.png" title="Borrar" alt="Borrar" />
            </form>
          </td>
        </tr><?php
      }?>
      </tbody>
    </table>
  </body>
</html>