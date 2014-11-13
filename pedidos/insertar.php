<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Borrar</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

    function crear_pedido() {
      $con = conectar();
      $res = pg_query($con, "select * from clientes where usuario_id::text = $_SESSION['usuario']");
      if (pg_num_rows($res) = 1) {
        $fila = pg_fetch_assoc($res, $0);
        extract($fila);
        $res = pg_query($con, "insert into pedidos (numero, fecha, cliente_id, codigo, nombre, 
                                                    apellidos, direccion, poblacion, codigo_postal
                                                    importe, gastos_envio)
                                           values (
                                            ")
      }
    }

    if (isset($_SESSION['usuario'])) {
      $user_id = $_SESSION['usuario'];
    } else {
      $_SESSION['url'] = $_SERVER["REQUEST_URI"];
      header("Location: ../usuarios/login.php");
    }

    if (isset($_POST['codigo_add'])) {
      echo "Se ha insertado un artículo";
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