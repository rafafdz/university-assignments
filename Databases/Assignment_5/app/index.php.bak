<?php
include('templates/header.html');
// Initialize the session
session_start();
?>


<body>
<nav class="navbar navbar-dark bg-dark">
  <form class="form-inline">
<?php if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){ ?>
        <a href="login/login.php" class="btn btn-sm btn-outline-primary mr-2" type="button">Iniciar Sesion</a>
        <a href="login/register.php" class="btn btn-sm btn-outline-success" type="button">Registrarse</a>
<?php } ?>
<?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){ ?>
        <a href="login/welcome.php" class="btn btn-sm btn-primary mr-2" type="button">Ver perfil</a>
<?php } ?>
  </form>
</nav>

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


<?php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  echo "<h3>Busquedas por nombre</h3>";
  echo "<form action='busquedas.php' method='post'>";
  echo "<input type='submit' value='Ir al menu de busquedas'>";
  echo "</form>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<h3>Realizar una reserva</h3>";
  echo "<form action='reservar.php' method='post'>";
  echo "<input type='submit' value='Reservar habitacion'>";
  echo "</form>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<h3>Marcar restaurante como favorito</h3>";
  echo "<form action='favorito.php' method='post'>";
  echo "<input type='submit' value='Marcar restaurante favorito'>";
  echo "</form>";
  echo "</form>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<h3>Ingresar a un Sendero</h3>";
  echo "<form action='ingreso_sendero.php' method='post'>";
  echo "<input type='submit' value='Ingresar'>";
  echo "</form>";
  echo "<br>";
  echo "<br>";
  echo "<br>";
  echo "<h3>Cambiar estado de un Sendero</h3>";
  echo "<form action='cambiar_estado.php' method='post'>";
  echo "<input type='submit' value='Ingresar'>";
  echo "</form>";
}
?>

</body>
</html>
