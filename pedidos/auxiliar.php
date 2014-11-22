<?php  // FUNCIONES AUXILIARES PARA INSERCION PEDIDOS

      function finalizar_pedido($numero) {
      if ($_POST['id_unica'] == $_SESSION['id_unica']) {
        $usuario_id = $_SESSION['usuario'];
        $con = conectar();
        $res = pg_query($con, "select * from clientes where usuario_id::text = '$usuario_id'");
        if (pg_affected_rows($res) == 1) {
          $fila = pg_fetch_assoc($res, 0);
          extract($fila);
          $id = (int)($id);
          $codigo = (int)($codigo);
          $total_pedido = $_SESSION['total_pedido'];

          $res = pg_query($con, "insert into pedidos (numero, fecha, cliente_id, codigo, nombre, 
                                                      apellidos, dni, direccion, poblacion, codigo_postal,
                                                      importe, gastos_envio)
                                             values  ($numero, current_date, $id, $codigo,'$nombre',
                                                      '$apellidos', '$dni', '$direccion', '$poblacion',
                                                      '$codigo_postal', $total_pedido,0)");
          $res = pg_query($con, "select id from pedidos where numero = $numero");
          if (pg_affected_rows($res) == 1) { 
            $fila = pg_fetch_assoc($res, 0);
            extract($fila);
            insertar_lineas_pedido($id);
            $_SESSION['detalle_pedido'] = array(); // Vaciamos el array del pedido.
            $_SESSION['total_pedido'] = 0; // Ponemos a 0 el total del pedido.
            unset ($_SESSION['id_unica']); // Destruimos la variable de sesión única.S i hay refresco de
                                           //  pantalla no se realiza la inserción de datos en la BBDD.
          } else { // Si no existe ningún pedido con ese número (no se ha creado) devuelve 0
            return 0;
          }
        } else {
          echo "El usuario $usuario_id no tiene cuenta de cliente asignada";
          // Habría que redirigir a la página de creación de cliente y después devolverlo aquí.
        }
      } else {
        echo "El pedido ya había sido registrado";
      }
    }

    function vaciar_carro() {
      if ($_POST['id_unica'] == $_SESSION['id_unica']) {
        foreach ($_SESSION['detalle_pedido'] as $k => $v) {
          actualiza_stock($k, -($v[2])); // Restituimos las existencias de cada artículo
        }
            $_SESSION['detalle_pedido'] = array(); // Vaciamos el array del pedido.
            $_SESSION['total_pedido'] = 0; // Ponemos a 0 el total del pedido.
      }
    }

    function insertar_lineas_pedido($id) {
      $con = conectar();

      foreach ($_SESSION['detalle_pedido'] as $k => $v) {
      $res = pg_query($con, "insert into lineas_pedidos (pedido_id, codigo, descripcion, precio, cantidad)
                                                values  ($id, $k, '$v[0]', $v[1], $v[2])");
      }
      pg_close($con);
    }

    function calcular_numero_pedido() {
      $con = conectar();
      $res = pg_query($con, "select max(numero) as numero from pedidos");
      if (pg_affected_rows($res) == 1) {
        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
        $numero ++;
        return $numero;
        pg_close($con);
      } else { // Si no existe aún ningún pedido calcula el primer número para el primer pedido del año.
        $numero = (int)(date('y') . '000001');
        return $numero;
        pg_close($con);
      }

    }

    function insertar_articulo_pedido($codigo_art) {

      if ($_POST['id_unica'] == $_SESSION['id_unica'] && tiene_stock($codigo_art)) {      
        $codigo_art = (float)($codigo_art);

        actualiza_stock($codigo_art, 1);
        $_SESSION['listado_articulos'][$codigo_art][2] --;

        $con = conectar();
        $res = pg_query($con, "select descripcion, precio from articulos where codigo = $codigo_art");
        if (pg_affected_rows($res) == 1) {

          $fila = pg_fetch_assoc($res, 0);
          extract($fila);
          $cantidad = 1;
          $_SESSION['total_pedido'] += $precio;
          if (!isset($_SESSION['detalle_pedido'][$codigo_art])) {
            $_SESSION['detalle_pedido'][$codigo_art] = array($descripcion, $precio, $cantidad);
          } else {
            $_SESSION['detalle_pedido'][$codigo_art][2] +=  $cantidad;
          }
        }
      }
    }

    function borrar_articulo_pedido($codigo_art) {

      if ($_POST['id_unica'] == $_SESSION['id_unica'] && isset($_SESSION['detalle_pedido'][$codigo_art])) {
        $codigo_art = (float)($codigo_art);

        actualiza_stock($codigo_art, -1);
        $_SESSION['listado_articulos'][$codigo_art][2] ++;


        $con = conectar();
        $res = pg_query($con, "select precio from articulos where codigo = $codigo_art");
        if (pg_affected_rows($res) == 1) {

          $fila = pg_fetch_assoc($res, 0);
          extract($fila);
          $cantidad = 1;
          if (!isset($_SESSION['detalle_pedido'][$codigo_art])) {
            return;
          } else {
            if ($_SESSION['detalle_pedido'][$codigo_art][2] == 1) {
              unset($_SESSION['detalle_pedido'][$codigo_art]);
              $_SESSION['total_pedido'] -= $precio;
            } else {
              $_SESSION['detalle_pedido'][$codigo_art][2] -=  $cantidad;
              $_SESSION['total_pedido'] -= $precio;
            }
          }
        }
      }
    }

    function tiene_stock($codigo_art) {

      $codigo_art = (float)($codigo_art);
      $retorno = false;

      $con = conectar();
      $res = pg_query($con, "select existencias from articulos where codigo = $codigo_art");
      if (pg_affected_rows($res) == 1) {
        $fila = pg_fetch_assoc($res, 0);
        extract($fila);
        if ($existencias > 0 ) {
          $retorno =  true;
        }
      }
      return $retorno;
    }

    function actualiza_stock($codigo_art, $cantidad) {

      $codigo_art = (float)($codigo_art);

      $con = conectar();
      $res = pg_query($con, "update articulos SET existencias = existencias-($cantidad) where codigo = $codigo_art");

    }


   function cuenta_unidades() {
        $unidades = 0;
        foreach ($_SESSION['detalle_pedido'] as $k => $v) {
          $unidades += $v[2];
        }
        return $unidades;  
    }

    function total_articulos() {
      return count($_SESSION['listado_articulos']);
    }

    function rellenar_array_articulos($filtro, $order, $stock) {
        $_SESSION['listado_articulos'] = array(); // Crea el array para almacenar los artículos
        $con = conectar();
        if ($stock) {
          $res = pg_query($con, "select * from articulos where existencias>0 and upper(descripcion) like upper('%$filtro%') order by $order");
        } else {
          $res = pg_query($con, "select * from articulos where upper(descripcion) like upper('%$filtro%') order by $order");          
        }
        if (pg_affected_rows($res) >0) {
          for ($i=0; $i < pg_affected_rows($res) ; $i++) { 
            $fila = pg_fetch_assoc($res, $i);
            extract($fila);
            if (!isset($_SESSION['listado_articulos'][$codigo])) {
              $_SESSION['listado_articulos'][$codigo] = array($descripcion, $precio, $existencias);
            }
          }
        }
    }

  // FIN   FUNCIONES AUXILIARES PARA INSERCION PEDIDOS

