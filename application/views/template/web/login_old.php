

	 <div class="wrapper">
  <div class="left">
<br>
<br>
<br>    <img src="https://i.imgur.com/eN4AKys.png" alt="Rocket_image">
  </div>
  <div class="right">
    <div class="tabs">
      <ul>
        <li class="register_li">Ingresar</li>
        <li class="login_li">Registro</li>
      </ul>
    </div>

    <div class="register">

      <form class="login-form" name="form1" id="form_login" method="POST" onsubmit="return false">
      <div class="input_field">
      <input type="text" name="txt_usuario" id="txt_usuario" placeholder="USUARIO"/>
    </div>
    <div class="input_field">
      <input type="password" name="txt_pass" id="txt_pass" placeholder="CONTRASEÑA"/>
    </div>
      <div class="btn-contenido"><input type="submit" id="btnLogin" name="Submit" value="INGRESAR"></div><br><br>
    </form>
    </div>

    <div class="login">

      <form action="" method="POST" id="form_ingresar" onsubmit="return false">
          <div class="input_field">
            <input type="text" name="nombre" id="nombre" placeholder="NOMBRE" class="" >
          </div>
          <div class="input_field">
            <input type="text" name="apellido" id="apellido" placeholder="APELLIDO" class="required" >
          </div>
          <div class="input_field">
            <input type="text" name="user" id="user" placeholder="USUARIO" class="required" >
          </div>
          <div class="input_field">
            <input type="text" name="email" id="email" placeholder="CORREO ELECTRÓNICO" class="required" >
          </div>
          <div class="input_field">
            <input type="password" name="password" id="password" placeholder="CONTRASEÑA" class="required" >
          </div>
            <div class="btn-contenido">
              <input type="submit" name="action" id="action" value="CREAR ESTUDIANTE" ></div>
            <br><br>
      </form>
        </div>

      </div>

</div>