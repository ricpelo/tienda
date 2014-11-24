    <header>
      <div class="login">
        <a style="text-decoration:none" href="/tienda/index.php" title="Volver al inicio">
                          <img class="user" src="../images/home.png" alt="Inicio"/>
        </a><?php
        if (!isset($_SESSION['usuario'])) {?>
          <a style="text-decoration:none" href="/tienda/usuarios/login.php" title="Cerrar sesión => No esta logueado">
                        <img class="user" src="../images/logout.png" alt="Cerrar sesión => <?= $nick ?>"/>
          <a style="text-decoration:none" href="/tienda/clientes/registro.php" title="Cerrar sesión => No esta logueado">
                        <img class="user" src="../images/logout.png" alt="Cerrar sesión => <?= $nick ?>"/><?php
        } else {
         $user_id = comprobar_usuario();
         $nick = comprobar_nick($user_id);?>
          <a style="text-decoration:none" href="/tienda/usuarios/logout.php" title="Cerrar sesión => <?= $nick ?>">
                        <img class="user" src="../images/logout.png" alt="Cerrar sesión => <?= $nick ?>"/><?php          
        }?>     
        </a>
      </div>
    </header>