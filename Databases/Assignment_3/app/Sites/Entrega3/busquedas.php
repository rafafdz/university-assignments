<?php include('templates/header.html');   ?>

<h3>Buscar por nombre de hotel:</h3>

<form action="busquedas/hotel.php" method="post">
  Ingrese el nombre o la parte de nombre que desea buscar:
  <input type="text" name="nombre">
  <br/>
  <br/>
  <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<h3>Buscar por nombre de restaurant:</h3>

<form action="busquedas/restaurant.php" method="post">
  Ingrese el nombre o la parte de nombre que desea buscar:
  <input type="text" name="nombre">
  <br/>
  <br/>
  <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<h3>Buscar por nombre de agencia:</h3>

<form action="busquedas/agencia.php" method="post">
  Ingrese el nombre o la parte de nombre que desea buscar:
  <input type="text" name="nombre">
  <br/>
  <br/>
  <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>


<h3>Buscar por nombre de parque o sendero:</h3>

<form action="busquedas/parque_sendero.php" method="post">
  Ingrese el nombre o la parte de nombre que desea buscar:
  <input type="text" name="nombre">
  <br/>
  <br/>
  <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>


<h3>Buscar por nombre de vina:</h3>

<form action="busquedas/vina.php" method="post">
  Ingrese el nombre o la parte de nombre que desea buscar:
  <input type="text" name="nombre">
  <br/>
  <br/>
  <input type="submit" value="Buscar">
</form>
<br>
<br>
<br>

<?php include('templates/footer.html'); ?>
