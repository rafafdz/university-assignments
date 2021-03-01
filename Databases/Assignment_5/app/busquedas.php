<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
  header("location: login/login.php");
  exit;
}
?>



<?php include('templates/header.html'); ?>

<h3>Buscar por nombre de hotel:</h3>

<form action="busquedas/hotel.php" method="post">
    Ingrese el nombre o la parte de nombre que desea buscar:
    <input type="text" name="nombre">
    <br />
    <br />
    <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<h3>Buscar por nombre de restaurant:</h3>

<form action="busquedas/restaurant.php" method="post">
    Ingrese el nombre o la parte de nombre que desea buscar:
    <input type="text" name="nombre">
    <br />
    <br />
    <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<h3>Buscar por nombre de agencia:</h3>

<form action="busquedas/agencia.php" method="post">
    Ingrese el nombre o la parte de nombre que desea buscar:
    <input type="text" name="nombre">
    <br />
    <br />
    <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>


<h3>Buscar por nombre de parque o sendero:</h3>

<form action="busquedas/parque_sendero.php" method="post">
    Ingrese el nombre o la parte de nombre que desea buscar:
    <input type="text" name="nombre">
    <br />
    <br />
    <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>


<h3>Buscar por nombre de vina:</h3>

<form action="busquedas/vina.php" method="post">
    Ingrese el nombre o la parte de nombre que desea buscar:
    <input type="text" name="nombre">
    <br />
    <br />
    <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<br><br>
<form action="index.php" method="post">
    <input type="submit" value="Volver">
</form>
</body>

</html>