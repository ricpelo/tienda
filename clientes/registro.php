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

    function comprobar_codigo(){ //----------------------------------marca isaac
      global $errores;
      
      if (!isset($_POST['codigo'])) {
        $errores[]="El CODIGO no puede estár vacio";
      }
      if (strlen($_POST['codigo']) != 6 ) {
        $errores[]="El CODIGO no es valido. No tiene 6 digitos.";
      }
      $codigo=(int)(trim($_POST['codigo']));
      $con=conectar();
      $res=pg_query($con, "select codigo 
                  from clientes
                  where codigo= $codigo");
      
      if (pg_affected_rows($res)!=0) {
        $errores[]="El CODIGO ya está dado de alta en la BD";
      }
    }

    function comprobar_admin(){
      global $con;

      $usuario = trim($_SESSION['usuario']);

      $res = pg_query_params($con, "select *
                               from usuarios
                              where id = $1", [$usuario]);

      if (pg_num_rows($res) == 1){
        $fila = pg_fetch_assoc($res);

        return ($fila['rol_id'] != 1) ? FALSE : TRUE;
       
      }
    }

    function comprobar_errores(){
      global $errores;

      if(!empty($errores))
        throw new Exception();    
    }

    function comprobar_dni(){//--------------------------------------marca isaac
      global $errores;

      if (!isset($_POST['dni']) && strlen($_POST['dni']) == 0) {
        $errores[]="El valor DNI no puede estár vacio";
      }

      if (strlen($_POST['dni']) != 9 ) {
        $errores[]="El DNI no es valido. No tiene 9 digitos.";
      }

      $dni=trim($_POST['dni']);
      $con=conectar();
      $res=pg_query($con, "select dni
                  from clientes
                  where dni::text = '$dni'");

      if (pg_affected_rows($res)!=0) {
        $errores[]="El DNI ya está dado de alta en la BD";
      }
    }

    function comprobar_codigo_postal(){//----------------------------marca isaac
      global $errores;

      if (strlen($_POST['codigopostal']) != 5 ) {
        $errores[]="El CODIGO POSTAL no es valido. No tiene 5 digitos.";
      }
    }

    function comprobar_insertar($res){//-----------------------------marca isaac
      global $errores;
      
      if ($res == FALSE || pg_affected_rows($res)!=1) {
        $errores[]="No se ha podido insertar el cliente correctamente";
        comprobar_errores();
      }
    }

    function obtener_usuario(){
      $usuario = (isset($_SESSION['usuario'])) ?
                                        (int) trim($_SESSION['usuario']) : "";
      return $usuario;
    }

    function form_nuevo_usuario(){ 

    	
      global $con; ?>
		

		
      <h2>Insertar cliente</h2>
      <h3>Datos Personales</h3>
      <form action="registro.php" method="post">
        <label for="codigo">Codigo :</label>
        <input type="text" name="codigo" value="<?= (isset($_POST['codigo'])) ? $_POST['codigo'] : '' ?>"><br>
        <label for="nombre">Nombre : </label>
        <input type="text" name="nombre" value="<?= (isset($_POST['nombre'])) ? $_POST['nombre'] : '' ?>" ><br>
        <label for="apellidos">Apellidos : </label>
        <input type="text" name="apellidos" value="<?= (isset($_POST['apellidos'])) ? $_POST['apellidos'] : '' ?>"><br>
        <label for="dni">DNI :</label>
        <input type="text" name="dni" value="<?= (isset($_POST['dni'])) ? $_POST['dni'] : '' ?>"><br>
        <label for="direccion" >Dirección: </label>
        <input type="text" name="direccion" value="<?= (isset($_POST['direccion'])) ? $_POST['direccion'] : '' ?>"><br>
        <label for="poblacion">Población: </label>
        <input type="text" name="poblacion" value="<?= (isset($_POST['poblacion'])) ? $_POST['poblacion'] : '' ?>"><br>
        <label for="codigopostal">Código postal : </label>
        <input type="text" name="codigopostal" value="<?= (isset($_POST['codigopostal'])) ? $_POST['codigopostal'] : '' ?>"><br>   
        <h3>Datos de Usuario</h3>     
        <label for="nick">Nick</label>
        <input type="text" value="<?= (isset($_POST['nick'])) ? $_POST['nick'] : '' ?>" name ="nick"> <br />
        <label for="pass">Contraseña</label>
        <input type="password" name="password"> <br /> <?php
        	if (comprobar_admin()){
        		?>
        		<input type="radio" name="rol" value="1" >Administrador <br>
        		<input type="radio" name="rol" value="2" checked="checked">Cliente<br>
        		<?php
        	}else{
        		?><input type="hidden" name="rol" value="2"><?php
        	}
        	?>
        
        <input type="submit" value="Alta">        
      </form>

      <a href="index.php"><button>Cancelar</button></a> <?php
    }

    function limpiar_datos(){
      foreach ($_POST as $key => $value)
        $_POST["$key"] = trim($value);
    }

    function comprobar_restricciones(){
      global $errores;

      if(!(strlen($_POST['nick']) <= 15) || (strlen($_POST['nick']) == 0))
        $errores[] = "Error: Nick debe ser inferior o igual a 15 caracteres";

      if(strlen($_POST['password']) == 0){
        $errores[] = "Error: Debe introducir una contraseña";
      }
    }

    function pintar_usuario_insertado(){
      global $con;

      $nick = trim($_POST['nick']);

      $res = pg_query_params($con, "select *
                                      from usuarios
                                     where nick = $1", [$nick]);

      if (pg_num_rows($res) == 1){ ?>
        <p>El usuario <strong><?= $nick ?></strong> ha sido insertado 
                                                        correctamente</p> <br /> 
        <a href="altas.php"><button>Volver</button></a> <?php
      }

    }

    function insertar_cliente(){//-----------------------------------marca isaac
      global $con;

      extract($_POST);

      $res = pg_query_params($con, "select id
                                      from usuarios
                                     where nick = $1", [$nick]);
      $fila = pg_fetch_assoc($res);

      $usuario_id = $fila['id'];

      $res = pg_query($con,"begin");
      $res = pg_query($con,"lock table clientes in share mode");
      //sentencia sql que inserta un registro en la tabla
      $res = pg_query($con,"insert into clientes 
                (codigo, nombre, apellidos, dni, direccion, poblacion, codigo_postal, usuario_id)
                values ($codigo, '$nombre', '$apellidos', '$dni', '$direccion', '$poblacion', 
                  '$codigopostal', $usuario_id)");

      //comprobamos si la insercion se ha hecho correctamnte
      comprobar_insertar($res);
    }


    function insertar_usuario(){
      global $con;
      global $errores;

      extract($_POST);
      $password = md5($password);

      $res = pg_query_params($con, "select *
                                      from usuarios
                                     where nick = $1", [$nick]);

      if(pg_num_rows($res) == 0){
        
        $res = pg_query($con, "begin");
        $res = pg_query($con, "lock table usuarios in share mode");
        $res = pg_query_params($con, "insert into usuarios 
                                                  (nick, password, rol_id)
                                      values ($1, $2, $3)", 
                                                  [$nick, $password, $rol]);
        comprobar_insertar($res);
        $res = pg_query($con, "commit");
      }else{
        $errores[] = "Error: el nick ya existe";

        comprobar_errores();
      }
    }

    try{
      if(!isset($_POST['nick'])){
        comprobar_usuario();
        
      }else{
        limpiar_datos();

        comprobar_restricciones();
        comprobar_codigo();
        comprobar_dni();
        comprobar_codigo_postal();

        comprobar_errores();
        insertar_usuario();
        insertar_cliente();
        pintar_usuario_insertado();
      }
    }catch(Exception $e){
      foreach ($errores as $v) { ?>
        <p><?= $v ?></p> <?php
      } 

    }finally {
    	form_nuevo_usuario();
      $res = pg_query($con, "commit");
      pg_close($con);

    } ?>
  </body>
</html>