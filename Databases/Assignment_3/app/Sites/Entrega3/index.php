<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
     echo "<a href='login/login.php'>Iniciar Sesion</a>";
     echo "  ";
     echo "<a href='login/register.php'>Registrarse</a>";
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  echo "<a href='login/welcome.php'>Ver perfil</a>";
}
?>
<br>
<br>

<h3>Lista de hoteles</h3>
<form action="vistas/hoteles.php" method="post">
  <input type="submit" value="Mostrar hoteles">
</form>
<br>
<br>
<br>


<h3>Lista de Restaurants</h3>
<form action="vistas/restaurantes.php" method="post">
  <input type="submit" value="Mostrar restaurants">
</form>
<br>
<br>
<br>


<h3>Agencias de turismo por region</h3>
<form action="vistas/agencia_tourismo_por_region.php" method="post">
  <input type="submit" value="Mostrar regiones">
</form>
<br>
<br>
<br>


<h3>Lista de parques nacionales</h3>
<form action="vistas/parques_nacionales.php" method="post">
  <input type="submit" value="Mostrar parques nacionales">
</form>
<br>
<br>
<br>


<h3>Lista de vinas</h3>
<form action="vistas/vinas.php" method="post">
  <input type="submit" value="Mostrar vinas">
</form>
<br>
<br>
<br>


<h3>Lista de enoturismo</h3>
<form action="vistas/enoturismo.php" method="post">
  <input type="submit" value="Mostrar tours">
</form>
<br>
<br>
<br>


<h3>Busquedas por nombre</h3>
<form action="busquedas.php" method="post">
  <input type="submit" value="Ir al menu de busquedas">
</form>
<br>
<br>
<br>
