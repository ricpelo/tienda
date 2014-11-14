<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Alta de Usuarios</title>
  </head>
  <body><?php
    require '../comunes/auxiliar.php';

    $con = conectar();
    $errores = [];

    $_SESSION['usuario'] = 1;

    function comprobar_usuario(){
      if (!isset($_SESSION['usuario']))
        header("Location: ../usuarios/login.php"); 
    }

    function comprobar_errores(){
      global $errores;

      if(!empty($errores))
        throw new Exception();    
    }

    function obtener_usuario(){
      $usuario = (isset($_SESSION['usuario'])) ?
                                        (int) trim($_SESSION['usuario']) : "";
      return $usuario;
    }

    function form_nuevo_usuario(){ 
      global $con; ?>

      <form action="altas.php" method="post">
        <label for="nick">Nick</label>
        <input type="text" name="nick">
        <label for="pass">Contraseña</label>
        <input type="password" name="pass">
        <label for="rol">Rol</label>
        <select name="rol"> <?php
          $res = pg_query($con, "select * from roles");
          $fila = pg_fetch_all($res);

          for ($i = 0; $i < count($fila); $i++) { ?>
            <option name="id" value="<?= $fila[$i]['id'] ?>"> 
              <?= $fila[$i]['descripcion'] ?>
            </option> <?php
          } ?>

        </select>
      </form><?php
    }

    form_nuevo_usuario();

    ?>
  </body>
</html>