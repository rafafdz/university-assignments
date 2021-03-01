<?php include('../templates/header.html');   ?>

<body>
<?php
  #Llama a conexión, crea el objeto PDO y obtiene la variable $db
  require("../config/conexion.php");

  $nombre_hotel = $_POST["nombre"];
  $nombre_hotel = strval($nombre_hotel);

 	$query = "SELECT * FROM hoteles WHERE LOWER(hoteles.nombre) LIKE LOWER(?)";

	$result = $db2 -> prepare($query);
	$result -> execute(array("%$nombre_hotel%"));
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
    <form action="hotel_elegido.php" method="post">
      <input type="hidden" name="hotel" value="<?php echo htmlspecialchars($id); ?>" />
      <input type="submit" value="Mostrar datos del hotel">
    </form>
    <br>
    <br>
    <br>
  <?php endforeach; ?>

  <?php include('../templates/footer.html'); ?>
