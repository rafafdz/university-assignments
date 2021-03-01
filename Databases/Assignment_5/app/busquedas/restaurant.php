<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $nombre_restaurant = $_POST["nombre"];
  $nombre_restaurant = strval($nombre_restaurant);

 	$query = "SELECT * FROM restaurantes WHERE LOWER(restaurantes.nombre) LIKE LOWER(?)";

	$result = $db2 -> prepare($query);
	$result -> execute(array("%$nombre_restaurant%"));
	$dataCollected = $result -> fetchAll();
  ?>

  <table>
    <tr>
      <th>Nombre</th>
    </tr>
  </table>

  <?php foreach ($dataCollected as $p): ?>
    <?php echo "<tr> <td>$p[2]</td> </tr>"; ?>
    <?php $id = $p[0] ?>
    <form action="restaurant_elegido.php" method="post">
      <input type="hidden" name="restaurant" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar datos del restaurant">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
